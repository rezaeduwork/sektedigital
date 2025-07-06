<?php

namespace App\Livewire\Store;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Whatsapp extends Component
{
  public $store;
  public $whatsapp;
  public $qrCode;
  public $status;
  public $phoneNumber;
  public $isLoading = false;
  public $connectionError = null;
  public $errorTimestamp = null;

  public function mount()
  {
    // Get current user's store
    $this->store = auth()->user()->store;

    // Get or create store WhatsApp record
    $this->loadWhatsappData();
  }

  public function loadWhatsappData()
  {
    $this->whatsapp = $this->store->whatsapp;

    if (!$this->whatsapp) {
      // Create a new WhatsApp connection for this store
      $this->whatsapp = $this->store->whatsapp()->create([
        'status' => 'disconnected',
      ]);
    }

    $oldStatus = $this->status;
    $this->qrCode = $this->whatsapp->qr_code;
    $this->status = $this->whatsapp->status;
    $this->phoneNumber = $this->whatsapp->phone_number;

    // If status changed, dispatch an event
    if ($oldStatus !== $this->status) {
      $this->dispatch('statusUpdated', $this->status);
    }
  }

  public function connectWhatsapp()
  {
    $this->isLoading = true;
    $this->connectionError = null;
    $id = 'CID_' . $this->whatsapp->id;
    $connect = (new \App\Services\WhatsappBotService())->connect($id);
    if ($connect['success']) {
      $this->status = 'connecting';
      $this->whatsapp->update(['status' => 'connecting']);
      $this->dispatch('statusUpdated', $this->status);
      $this->dispatch('initQrScanner');
    } else {
      // Handle connection error
      // Get error message from response if available
      $errorMessage = $connect['message'] ?? 'Failed to connect to WhatsApp gateway.';
      $this->connectionError = $errorMessage;
      $this->errorTimestamp = now()->timestamp;
    }

    $this->isLoading = false;
  }

  public function disconnectWhatsapp()
  {
    $id = 'CID_' . $this->whatsapp->id;
    $this->isLoading = true;

    $disconnect = (new \App\Services\WhatsappBotService())->disconnect($id);
    if ($disconnect['success']) {
      $this->status = 'disconnected';
      $this->whatsapp->update(['status' => 'disconnected']);
      $this->qrCode = null;
      $this->dispatch('statusUpdated', $this->status);
    } else {
      // Handle disconnection error
      session()->flash('error', $disconnect['message'] ?? 'Failed to disconnect from WhatsApp gateway.');
    }

    $this->isLoading = false;
  }
  public function refreshQrCode()
  {
    try {
      // Format the connection ID as CID_{id} for the NodeJS gateway
      $formattedConnectionId = 'CID_' . $this->whatsapp->id;

      // Only disconnect if we're not already connecting
      // This prevents the "User initiated disconnect" message when just refreshing a QR code
      if ($this->status !== 'connecting') {
        // First disconnect the current connection if we're connected
        $disconnectResponse = Http::delete(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection/' . $formattedConnectionId);

        // Short delay to ensure disconnection is processed
        usleep(500000); // 500ms delay
      }

      // Reconnect to get a fresh QR code
      $response = Http::post(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection', [
        'connectionId' => $formattedConnectionId,
        'refreshQr' => true  // Add a flag to indicate this is just a QR refresh
      ]);

      if ($response->successful()) {
        // Update status to connecting
        $this->status = 'connecting';
        $this->whatsapp->update(['status' => 'connecting']);
        $this->dispatch('statusUpdated', $this->status);

        // Reload data after a short delay to allow QR code to be generated
        $this->dispatch('initQrScanner');
      } else {
        $this->connectionError = 'Failed to refresh QR code: ' . ($response->json('message') ?? $response->body());
      }
    } catch (\Exception $e) {
      $this->connectionError = 'Error refreshing QR code: ' . $e->getMessage();
    }
  }

  public function getWhatsappStatusClass()
  {
    switch ($this->status) {
      case 'connected':
        return 'text-green-600';
      case 'connecting':
        return 'text-yellow-600';
      default:
        return 'text-red-600';
    }
  }

  public function clearConnectionError()
  {
    $this->connectionError = null;
    $this->errorTimestamp = null;
  }

  public function connectionTimeout()
  {
    if ($this->status === 'connecting') {
      if (!$this->qrCode) {
        // QR code was never received
        $this->connectionError = "Connection timeout. QR code not received from WhatsApp gateway. Please try again.";
      } else {
        // QR code was received but never scanned
        $this->connectionError = "Connection timeout. QR code was not scanned in time. Please try again.";
      }

      $this->status = 'disconnected';
      // Update the status in the database
      $this->whatsapp->update([
        'status' => 'disconnected',
        'qr_code' => null // Clear the QR code
      ]);

      // Dispatch status updated event to trigger UI updates and timer resets
      $this->dispatch('statusUpdated', 'disconnected');

      // Try to notify the gateway that we're disconnecting due to timeout
      try {
        $formattedConnectionId = 'CID_' . $this->whatsapp->id;
        Http::delete(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection/' . $formattedConnectionId . '/timeout');
      } catch (\Exception $e) {
        // Just log the error, don't show to user
        \Log::error('Failed to notify gateway of connection timeout: ' . $e->getMessage());
      }
    }
  }

  // Properties for custom commands management
  public $storeCommands = [];
  public $masterCommands = [];
  public $masterCategories = [];
  public $uncategorizedMasterCommands = [];
  public $showAddCommandModal = false;
  public $showEditCommandModal = false;
  public $showDeleteModal = false;
  public $customCommandNames = [];
  public $editingCommand = null;
  public $deleteCommandId = null;
  public $selectedCategory = null;

  public function render()
  {
    // Get custom commands for this store with their master commands
    $storeCommands = \App\Models\WhatsappBotCommand::where('store_id', $this->store->id)
      ->where('is_master', false)
      ->with('masterCommand') // Eager load the master command relationship
      ->orderBy('command')
      ->get();

    // Get master categories
    $masterCategories = \App\Models\WhatsappBotCommandCategory::where('is_master', true)
      ->orderBy('display_order')
      ->with(['commands' => function ($query) {
        $query->where('is_master', true);
      }])
      ->get();

    // Get uncategorized master commands
    $uncategorizedMasterCommands = \App\Models\WhatsappBotCommand::where('is_master', true)
      ->whereNull('category_id')
      ->orderBy('command')
      ->get();

    // Keep old reference to master commands for backward compatibility
    $masterCommands = \App\Models\WhatsappBotCommand::where('is_master', true)
      ->orderBy('command')
      ->get();

    $this->storeCommands = $storeCommands;
    $this->masterCategories = $masterCategories;
    $this->uncategorizedMasterCommands = $uncategorizedMasterCommands;
    $this->masterCommands = $masterCommands;

    return view('livewire.store.whatsapp', [
      'storeCommands' => $storeCommands,
      'masterCommands' => $masterCommands
    ])->layout('components.layouts.app-dashboard');
  }

  /**
   * Handle successful QR code generation
   */
  public function qrCodeGenerated()
  {
    // If we got a QR code, update the status to ensure we're in connecting state
    if ($this->qrCode && $this->status !== 'connected') {
      $oldStatus = $this->status;
      $this->status = 'connecting';
      $this->whatsapp->update(['status' => 'connecting']);

      // If status changed, dispatch an event to trigger UI updates
      if ($oldStatus !== 'connecting') {
        $this->dispatch('statusUpdated', 'connecting');
      }
    }
  }

  protected function getListeners()
  {
    return [
      'connectionTimeout',
      'refreshQrCode',
      'initQrScanner',
      'qrCodeGenerated'
    ];
  }

  /**
   * Show the add command modal
   */
  public function showAddCommandModal()
  {
    $this->showAddCommandModal = true;
    $this->dispatch('addCommandModalShown');
  }

  /**
   * Open the add command modal - alias for blade template
   */
  public function openAddCommandModal()
  {
    $this->showAddCommandModal();
  }

  /**
   * Show the edit command modal
   */
  public function editCommand($commandId)
  {
    $command = \App\Models\WhatsappBotCommand::where('store_id', $this->store->id)
      ->where('id', $commandId)
      ->first();

    if ($command) {
      $this->editingCommand = $command;
      $this->showEditCommandModal = true;
      $this->dispatch('editCommandModalShown');
    }
  }

  /**
   * Add a custom command based on a master command or import a category of commands
   *
   * @param mixed $id ID of the category or command to import
   * @param string $type 'category' or 'command'
   */
  public function addCommand($id, $type = 'command')
  {
    if ($type == 'category') {
      return $this->importCategory($id);
    }

    $masterCommand = \App\Models\WhatsappBotCommand::where('is_master', true)
      ->where('id', $id)
      ->first();

    if (!$masterCommand) {
      session()->flash('error', 'Master command not found.');
      return;
    }

    $customName = isset($this->customCommandNames[$id]) && !empty($this->customCommandNames[$id])
      ? $this->customCommandNames[$id]
      : $masterCommand->command;

    // Create a new custom command based on the master command
    \App\Models\WhatsappBotCommand::create([
      'store_id' => $this->store->id,
      'is_master' => false,
      'master_command_id' => $masterCommand->id,
      'name' => $customName,
      'command' => $customName,
      'description' => $masterCommand->description,
      'response_template' => $masterCommand->response_template,
      'parameters' => $masterCommand->parameters,
      'category_id' => $masterCommand->category_id,
      'handler_class' => $masterCommand->handler_class,
      'is_active' => true,
      'display_order' => $masterCommand->display_order
    ]);

    $this->customCommandNames[$id] = '';
    $this->showAddCommandModal = false;
    session()->flash('success', 'Command added successfully.');
  }

  /**
   * Import all commands from a category
   *
   * @param int $categoryId
   * @return void
   */
  public function importCategory($categoryId)
  {
    // Find the category
    $category = \App\Models\WhatsappBotCommandCategory::where('is_master', true)
      ->where('id', $categoryId)
      ->first();

    if (!$category) {
      session()->flash('error', 'Category not found.');
      return;
    }

    // Get all commands in this category
    $commands = \App\Models\WhatsappBotCommand::where('is_master', true)
      ->where('category_id', $categoryId)
      ->get();

    if ($commands->isEmpty()) {
      session()->flash('error', 'No commands found in this category.');
      return;
    }

    // Create local category first (if it doesn't exist)
    $storeCategory = \App\Models\WhatsappBotCommandCategory::firstOrCreate(
      [
        'store_id' => $this->store->id,
        'name' => $category->name
      ],
      [
        'description' => $category->description,
        'is_master' => false,
        'display_order' => $category->display_order,
        'is_active' => true
      ]
    );

    $importedCount = 0;
    $errorCount = 0;

    // Import each command
    foreach ($commands as $command) {
      // Check if command already exists for this store
      $existingCommand = \App\Models\WhatsappBotCommand::where('store_id', $this->store->id)
        ->where('command', $command->command)
        ->first();

      if ($existingCommand) {
        $errorCount++;
        continue; // Skip this command
      }

      // Create new command for this store
      \App\Models\WhatsappBotCommand::create([
        'store_id' => $this->store->id,
        'is_master' => false,
        'master_command_id' => $command->id,
        'name' => $command->name ?? $command->command,
        'command' => $command->command,
        'description' => $command->description,
        'response_template' => $command->response_template,
        'parameters' => $command->parameters,
        'category_id' => $storeCategory->id,
        'handler_class' => $command->handler_class,
        'is_active' => true,
        'display_order' => $command->display_order
      ]);

      $importedCount++;
    }

    $this->showAddCommandModal = false;

    if ($importedCount > 0) {
      if ($errorCount > 0) {
        session()->flash('success', "Imported {$importedCount} commands. {$errorCount} commands were skipped because they already exist.");
      } else {
        session()->flash('success', "Successfully imported {$importedCount} commands from category '{$category->name}'.");
      }
    } else {
      session()->flash('error', "No commands were imported. They may already exist in your store.");
    }
  }

  /**
   * Update a custom command
   */
  public function updateCommand()
  {
    if (!$this->editingCommand) {
      return;
    }

    $command = \App\Models\WhatsappBotCommand::where('store_id', $this->store->id)
      ->where('id', $this->editingCommand['id'])
      ->first();

    if ($command) {
      $command->update([
        'command' => $this->editingCommand['command'],
        'description' => $this->editingCommand['description'],
        'response_template' => $this->editingCommand['response_template'],
        'is_active' => $this->editingCommand['is_active']
      ]);

      session()->flash('success', 'Command updated successfully.');
    }

    $this->editingCommand = null;
    $this->showEditCommandModal = false;
  }

  /**
   * Confirm command deletion
   */
  public function confirmCommandDeletion($commandId)
  {
    $this->deleteCommandId = $commandId;
    $this->showDeleteModal = true;
    $this->dispatch('deleteCommandModalShown');
  }

  /**
   * Delete a custom command
   */
  public function deleteCommand()
  {
    if (!$this->deleteCommandId) {
      return;
    }

    $command = \App\Models\WhatsappBotCommand::where('store_id', $this->store->id)
      ->where('id', $this->deleteCommandId)
      ->first();

    if ($command) {
      $command->delete();
      session()->flash('success', 'Command deleted successfully.');
    }

    $this->deleteCommandId = null;
    $this->showDeleteModal = false;
  }

  /**
   * Close all modals
   */
  public function closeModal()
  {
    $this->showAddCommandModal = false;
    $this->showEditCommandModal = false;
    $this->showDeleteModal = false;
    $this->editingCommand = null;
    $this->deleteCommandId = null;
  }
}
