<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\WhatsappBotCommand;

class GlobalWhatsappCommands extends Component
{
  public $editingCommand = false;
  public $commandId;
  public $command;
  public $description;
  public $responseTemplate;
  public $displayOrder = 0;
  public $isActive = true;
  public $parameters = '';

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

  public function render()
  {
    $commands = WhatsappBotCommand::whereNull('store_id')
      ->orderBy('display_order', 'asc')
      ->paginate(10);

    return view('livewire.admin.global-whatsapp-commands', [
      'commands' => $commands
    ])->layout('components.layouts.app-dashboard');
  }

  /**
   * Reset the form fields
   */
  public function resetFields()
  {
    $this->reset(['editingCommand', 'commandId', 'command', 'description', 'responseTemplate', 'displayOrder', 'isActive', 'parameters']);
    $this->displayOrder = 0;
    $this->isActive = true;
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
    $command = WhatsappBotCommand::findOrFail($id);

    // Ensure we're editing a global command
    if (!is_null($command->store_id)) {
      session()->flash('error', 'You can only edit global commands here.');
      return;
    }

    $this->commandId = $command->id;
    $this->command = $command->command;
    $this->description = $command->description;
    $this->responseTemplate = $command->response_template;
    $this->displayOrder = $command->display_order;
    $this->isActive = $command->is_active;
    $this->parameters = is_array($command->parameters) ? implode(',', $command->parameters) : '';

    $this->editingCommand = true;
  }

  /**
   * Save a new or existing command
   */
  public function saveCommand()
  {
    $this->validate();

    // Convert parameters from comma-separated string to array
    $parametersArray = $this->parameters ? array_map('trim', explode(',', $this->parameters)) : null;

    $data = [
      'command' => $this->command,
      'description' => $this->description,
      'response_template' => $this->responseTemplate,
      'display_order' => $this->displayOrder,
      'is_active' => $this->isActive,
      'parameters' => $parametersArray,
      'store_id' => null // This is a global command
    ];

    if ($this->commandId) {
      // Update existing command
      $command = WhatsappBotCommand::find($this->commandId);

      // Check if this is a global command
      if (!is_null($command->store_id)) {
        session()->flash('error', 'You can only edit global commands here.');
        return;
      }

      $command->update($data);
      session()->flash('success', 'Global command updated successfully.');
    } else {
      // Create new command
      WhatsappBotCommand::create($data);
      session()->flash('success', 'Global command created successfully.');
    }

    $this->resetFields();
  }

  /**
   * Show delete confirmation modal
   */
  public function confirmDelete($id)
  {
    $command = WhatsappBotCommand::find($id);

    if (!$command || !is_null($command->store_id)) {
      session()->flash('error', 'You can only delete global commands here.');
      return;
    }

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

      if (!$command || !is_null($command->store_id)) {
        session()->flash('error', 'You can only delete global commands here.');
        $this->deleteId = null;
        $this->showDeleteModal = false;
        return;
      }

      $command->delete();
      session()->flash('success', 'Global command deleted successfully.');
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

    // Ensure it's a global command
    if (!is_null($command->store_id)) {
      session()->flash('error', 'You can only modify global commands here.');
      return;
    }

    $command->is_active = !$command->is_active;
    $command->save();

    session()->flash('success', 'Command status updated.');
  }
}
