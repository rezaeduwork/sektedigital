<?php

namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\WhatsappBotCommand;
use App\Models\WhatsappBotCommandCategory;
use Livewire\WithPagination;

class ImportWhatsappBotCommands extends Component
{
  use WithPagination;

  public $store;
  public $selectedCategory = null;
  public $showUncategorizedCommands = false;

  // For import tracking
  public $availableCommands = [];
  public $selectedCommands = [];

  // For modal control
  public $showImportModal = false;

  public function mount()
  {
    $this->store = auth()->user()->store;

    // Load available commands based on current selection
    $this->loadAvailableCommands();
  }

  public function render()
  {
    // Get categories for the dropdown
    $categories = WhatsappBotCommandCategory::where(function ($query) {
      // Include store categories and global categories that the store has imported
      $query->where('store_id', $this->store->id)
        ->orWhere('is_master', true);
    })
      ->orderBy('is_master', 'desc') // Master categories first
      ->orderBy('display_order')
      ->get();

    return view('livewire.store.import-whatsapp-bot-commands', [
      'categories' => $categories,
    ])->layout('components.layouts.app-dashboard');
  }

  /**
   * Load available commands based on current filter
   */
  public function loadAvailableCommands()
  {
    // Start with global master commands
    $query = WhatsappBotCommand::where('is_master', true);

    // Filter by category if selected
    if ($this->selectedCategory) {
      $query->where('category_id', $this->selectedCategory);
    } else if ($this->showUncategorizedCommands) {
      $query->whereNull('category_id');
    }

    // Exclude commands that this store has already imported
    $query->whereDoesntHave('customCommands', function ($q) {
      $q->where('store_id', $this->store->id);
    });

    $this->availableCommands = $query->orderBy('display_order')->get();
  }

  /**
   * Update category filter
   */
  public function updatedSelectedCategory()
  {
    $this->loadAvailableCommands();
  }

  /**
   * Toggle showing uncategorized commands
   */
  public function toggleUncategorizedCommands()
  {
    $this->showUncategorizedCommands = !$this->showUncategorizedCommands;
    $this->selectedCategory = null; // Reset category selection
    $this->loadAvailableCommands();
  }

  /**
   * Show import modal
   */
  public function showImportModal()
  {
    $this->selectedCommands = [];
    $this->showImportModal = true;
  }

  /**
   * Cancel import
   */
  public function cancelImport()
  {
    $this->selectedCommands = [];
    $this->showImportModal = false;
  }

  /**
   * Import selected commands
   */
  public function importCommands()
  {
    if (empty($this->selectedCommands)) {
      session()->flash('error', 'Please select at least one command to import.');
      return;
    }

    $importedCount = 0;

    foreach ($this->selectedCommands as $commandId) {
      $masterCommand = WhatsappBotCommand::where('is_master', true)
        ->where('id', $commandId)
        ->first();

      if ($masterCommand) {
        // Create a store-specific copy
        WhatsappBotCommand::create([
          'store_id' => $this->store->id,
          'is_master' => false,
          'master_command_id' => $masterCommand->id,
          'command' => $masterCommand->command,
          'name' => $masterCommand->name ?? $masterCommand->command,
          'description' => $masterCommand->description,
          'response_template' => $masterCommand->response_template,
          'parameters' => $masterCommand->parameters,
          'is_active' => true,
          'display_order' => $masterCommand->display_order,
          'category_id' => $masterCommand->category_id,
          'handler_class' => $masterCommand->handler_class
        ]);

        $importedCount++;
      }
    }

    $this->showImportModal = false;
    $this->selectedCommands = [];

    if ($importedCount > 0) {
      session()->flash('success', "Successfully imported {$importedCount} commands.");
    } else {
      session()->flash('error', 'No commands were imported.');
    }

    // Refresh available commands
    $this->loadAvailableCommands();
  }
}
