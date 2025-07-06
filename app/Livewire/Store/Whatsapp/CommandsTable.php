<?php

namespace App\Livewire\Store\Whatsapp;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\WhatsappBotCommand;
use App\Models\WhatsappBotCommandCategory;
use Livewire\WithPagination;

class CommandsTable extends Component
{
  use WithPagination;

  public $store;
  public $showAddCommandModal = false;
  public $showEditCommandModal = false;
  public $showDeleteModal = false;
  public $customCommandNames = [];
  public $editingCommand = null;
  public $deleteCommandId = null;

  public function mount()
  {
    $this->store = auth()->user()->store;
  }

  /**
   * Show the add command modal
   */
  public function openAddCommandModal()
  {
    $this->showAddCommandModal = true;
    $this->dispatch('addCommandModalShown');
  }

  /**
   * Show the edit command modal
   */
  public function openEditCommandModal($commandId)
  {
    $command = WhatsappBotCommand::where('store_id', $this->store->id)
      ->where('id', $commandId)
      ->first();

    if ($command) {
      $this->editingCommand = $command->toArray(); // Ensure array for Livewire binding
      $this->showEditCommandModal = true;
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
   * Update a custom command
   */
  public function updateCommand()
  {
    if (!$this->editingCommand) {
      return;
    }

    $command = WhatsappBotCommand::where('store_id', $this->store->id)
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
  public function openDeleteCommandModal($commandId)
  {
    $this->deleteCommandId = $commandId;
    $this->showDeleteModal = true;
  }

  /**
   * Delete a custom command
   */
  public function deleteCommand()
  {
    if (!$this->deleteCommandId) {
      return;
    }

    $command = WhatsappBotCommand::where('store_id', $this->store->id)
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
   * Import all commands from a category
   * Only one category can be active at a time per store
   *
   * @param int $categoryId
   * @return void
   */
  public function importCategory($categoryId)
  {
    // Find the master category
    $category = \App\Models\WhatsappBotCommandCategory::where('is_master', true)
      ->where('id', $categoryId)
      ->with(['commands' => function ($query) {
        $query->where('is_master', true);
      }])
      ->first();

    if (!$category || $category->commands->count() == 0) {
      session()->flash('error', 'Category not found or contains no commands.');
      return;
    }

    // Begin transaction to ensure data consistency
    \DB::beginTransaction();

    try {
      // 1. Check if store already has a category
      $existingCategory = \App\Models\WhatsappBotCommandCategory::where('store_id', $this->store->id)
        ->where('is_master', false)
        ->first();

      // 2. If a category exists, delete its commands and the category itself
      if ($existingCategory) {
        // Delete all commands tied to the existing category
        \App\Models\WhatsappBotCommand::where('store_id', $this->store->id)
          ->where('category_id', $existingCategory->id)
          ->delete();

        // Delete the existing category
        $existingCategory->delete();
      }

      // 3. Create a new store-specific version of the category
      $storeCategory = \App\Models\WhatsappBotCommandCategory::create([
        'store_id' => $this->store->id,
        'is_master' => false,
        'name' => $category->name,
        'description' => $category->description,
        'display_order' => $category->display_order,
        'is_active' => true
      ]);

      // 4. Get IDs of master commands already added as custom commands (uncategorized ones)
      $addedMasterIds = \App\Models\WhatsappBotCommand::where('store_id', $this->store->id)
        ->where('is_master', false)
        ->whereNotNull('master_command_id')
        ->whereNull('category_id')  // Only check uncategorized commands
        ->pluck('master_command_id')
        ->toArray();

      $importedCount = 0;
      $errorCount = 0;

      // 5. Import all commands from the selected category
      foreach ($category->commands as $command) {
        // Skip if already added as an individual uncategorized command
        if (in_array($command->id, $addedMasterIds)) {
          $errorCount++;
          continue;
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

      \DB::commit();

      $this->showAddCommandModal = false;

      if ($importedCount > 0) {
        if ($existingCategory) {
          session()->flash('success', "Previous category has been replaced. Successfully imported {$importedCount} commands from category '{$category->name}'.");
        } else {
          session()->flash('success', "Successfully imported {$importedCount} commands from category '{$category->name}'.");
        }
      } else {
        session()->flash('error', "No commands were imported. They may already exist in your store as individual commands.");
      }
    } catch (\Exception $e) {
      \DB::rollBack();
      session()->flash('error', "Error importing category: " . $e->getMessage());
    }
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

  public function render()
  {
    // Get custom commands for this store with their master commands
    $storeCommands = WhatsappBotCommand::where('store_id', $this->store->id)
      ->where('is_master', false)
      ->with('masterCommand') // Eager load the master command relationship
      ->orderBy('command')
      ->paginate(10);

    // Check if the store already has a category
    $currentStoreCategory = WhatsappBotCommandCategory::where('store_id', $this->store->id)
      ->where('is_master', false)
      ->first();

    // Get current category's master ID if it exists
    $currentCategoryMasterId = null;
    if ($currentStoreCategory) {
      // Try to find matching master category by name
      $matchingMasterCategory = WhatsappBotCommandCategory::where('is_master', true)
        ->where('name', $currentStoreCategory->name)
        ->first();

      if ($matchingMasterCategory) {
        $currentCategoryMasterId = $matchingMasterCategory->id;
      }
    }

    // Get IDs of master commands already added as custom commands
    $addedMasterIds = WhatsappBotCommand::where('store_id', $this->store->id)
      ->where('is_master', false)
      ->whereNotNull('master_command_id')
      ->pluck('master_command_id')
      ->toArray();

    // Get master categories with their commands
    $masterCategories = WhatsappBotCommandCategory::where('is_master', true)
      ->orderBy('display_order')
      ->with(['commands' => function ($query) use ($addedMasterIds) {
        $query->where('is_master', true);
        // We no longer filter out added commands when switching categories
        // ->whereNotIn('id', $addedMasterIds);
      }])
      ->get();

    // Get uncategorized master commands that are NOT already added
    $uncategorizedMasterCommands = WhatsappBotCommand::where('is_master', true)
      ->whereNull('category_id')
      ->whereNotIn('id', $addedMasterIds)
      ->orderBy('command')
      ->get();

    // Keep old reference to master commands for backward compatibility
    $masterCommands = WhatsappBotCommand::where('is_master', true)
      ->whereNotIn('id', $addedMasterIds)
      ->orderBy('command')
      ->get();

    return view('livewire.store.whatsapp.commands-table', [
      'storeCommands' => $storeCommands,
      'masterCommands' => $masterCommands,
      'masterCategories' => $masterCategories,
      'uncategorizedMasterCommands' => $uncategorizedMasterCommands,
      'currentStoreCategory' => $currentStoreCategory,
      'currentCategoryMasterId' => $currentCategoryMasterId
    ]);
  }
}
