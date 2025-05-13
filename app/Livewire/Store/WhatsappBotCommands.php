<?php

namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\WhatsappBotCommand;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;

class WhatsappBotCommands extends Component
{
  use WithPagination;

  public $store;
  public $editingCommand = false;
  public $commandId;
  public $command;
  public $description;
  public $responseTemplate;
  public $displayOrder = 0;
  public $isActive = true;
  public $parameters = '';
  public $isGlobalCommand = false; // New property for global commands

  // For confirmation modal
  public $showDeleteModal = false;
  public $deleteId = null;

  protected $rules = [
    'command' => 'required|string|max:50',
    'description' => 'required|string|max:255',
    'responseTemplate' => 'required|string',
    'displayOrder' => 'required|integer|min:0',
    'isActive' => 'boolean',
    'parameters' => 'nullable|string'
  ];

  public function mount()
  {
    $this->store = auth()->user()->store;
  }

  public function render()
  {
    // Get both store-specific and global commands
    $commands = WhatsappBotCommand::forStore($this->store->id)
      ->orderBy('display_order', 'asc')
      ->orderByRaw('store_id IS NULL ASC') // Show store-specific commands first
      ->paginate(10);

    return view('livewire.store.whatsapp-bot-commands', [
      'commands' => $commands
    ])->layout('components.layouts.app-dashboard');
  }

  /**
   * Reset the form fields
   */
  public function resetFields()
  {
    $this->reset([
      'editingCommand',
      'commandId',
      'command',
      'description',
      'responseTemplate',
      'displayOrder',
      'isActive',
      'parameters',
      'isGlobalCommand'
    ]);
    $this->displayOrder = 0;
    $this->isActive = true;
    $this->isGlobalCommand = false;
  }

  /**
   * Show the form to create a new command
   */
  public function createCommand()
  {
    $this->resetFields();
    $this->editingCommand = true;
  }

  /**
   * Edit an existing command
   */
  public function editCommand($id)
  {
    // Use the model directly instead of the relationship to access both global and store commands
    $command = WhatsappBotCommand::findOrFail($id);

    // Check if this store admin has permission to edit this command
    if (!is_null($command->store_id) && $command->store_id != $this->store->id) {
      session()->flash('error', 'You do not have permission to edit this command.');
      return;
    }

    $this->commandId = $command->id;
    $this->command = $command->command;
    $this->description = $command->description;
    $this->responseTemplate = $command->response_template;
    $this->displayOrder = $command->display_order;
    $this->isActive = $command->is_active;
    $this->parameters = is_array($command->parameters) ? implode(',', $command->parameters) : '';
    $this->isGlobalCommand = is_null($command->store_id);

    $this->editingCommand = true;
  }

  /**
   * Save a new or existing command
   */
  public function saveCommand()
  {
    $this->validate();

    // Check if user has permission to create global commands
    // Only Super Admin can create global commands (you should implement this check according to your permissions system)
    if ($this->isGlobalCommand && !auth()->user()->hasRole('super-admin')) {
      session()->flash('error', 'You do not have permission to create global commands.');
      return;
    }

    // Convert parameters from comma-separated string to array
    $parametersArray = $this->parameters ? array_map('trim', explode(',', $this->parameters)) : null;

    $data = [
      'command' => $this->command,
      'description' => $this->description,
      'response_template' => $this->responseTemplate,
      'display_order' => $this->displayOrder,
      'is_active' => $this->isActive,
      'parameters' => $parametersArray
    ];

    // Set store_id based on whether this is a global command
    $data['store_id'] = $this->isGlobalCommand ? null : $this->store->id;

    if ($this->commandId) {
      // Update existing command
      $command = WhatsappBotCommand::find($this->commandId);

      // Check if user has permission to edit this command
      if (!is_null($command->store_id) && $command->store_id != $this->store->id) {
        session()->flash('error', 'You do not have permission to edit this command.');
        return;
      }

      // Prevent changing a store command to global unless user is super-admin
      if ($command->store_id && $this->isGlobalCommand && !auth()->user()->hasRole('super-admin')) {
        session()->flash('error', 'You do not have permission to make a command global.');
        return;
      }

      $command->update($data);
      session()->flash('success', 'Command updated successfully.');
    } else {
      // Create new command
      WhatsappBotCommand::create($data);
      session()->flash('success', ($this->isGlobalCommand ? 'Global' : 'Store') . ' command created successfully.');
    }

    $this->resetFields();
  }

  /**
   * Show delete confirmation modal
   */
  public function confirmDelete($id)
  {
    $this->deleteId = $id;
    $this->showDeleteModal = true;
  }

  /**
   * Cancel delete operation
   */
  public function cancelDelete()
  {
    $this->deleteId = null;
    $this->showDeleteModal = false;
  }

  /**
   * Delete a command
   */
  public function deleteCommand()
  {
    if ($this->deleteId) {
      $command = WhatsappBotCommand::find($this->deleteId);

      if (!$command) {
        session()->flash('error', 'Command not found.');
        $this->deleteId = null;
        $this->showDeleteModal = false;
        return;
      }

      // Check if user has permission to delete this command
      if (is_null($command->store_id) && !auth()->user()->hasRole('super-admin')) {
        session()->flash('error', 'You do not have permission to delete global commands.');
        $this->deleteId = null;
        $this->showDeleteModal = false;
        return;
      }

      // Check if this is a store-specific command that belongs to this store
      if (!is_null($command->store_id) && $command->store_id != $this->store->id) {
        session()->flash('error', 'You do not have permission to delete this command.');
        $this->deleteId = null;
        $this->showDeleteModal = false;
        return;
      }

      $command->delete();
      session()->flash('success', 'Command deleted successfully.');
      $this->deleteId = null;
      $this->showDeleteModal = false;
    }
  }

  /**
   * Toggle command active status
   */
  public function toggleActive($id)
  {
    $command = WhatsappBotCommand::findOrFail($id);
    $command->is_active = !$command->is_active;
    $command->save();

    session()->flash('success', 'Command status updated.');
  }

  /**
   * Cancel editing/creating
   */
  public function cancel()
  {
    $this->resetFields();
  }
}
