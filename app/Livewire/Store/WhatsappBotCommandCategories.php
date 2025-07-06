<?php

namespace App\Livewire\Store;

use Livewire\Component;
use App\Models\WhatsappBotCommandCategory;
use Livewire\WithPagination;

class WhatsappBotCommandCategories extends Component
{
  use WithPagination;

  public $store;
  public $editingCategory = false;
  public $categoryId;
  public $name;
  public $description;
  public $displayOrder = 0;
  public $isActive = true;

  // For confirmation modal
  public $showDeleteModal = false;
  public $deleteId = null;

  // For importing master categories
  public $showImportModal = false;
  public $availableMasterCategories = [];
  public $selectedMasterCategories = [];

  protected $rules = [
    'name' => 'required|string|max:100',
    'description' => 'nullable|string|max:255',
    'displayOrder' => 'required|integer|min:0',
    'isActive' => 'boolean',
  ];

  public function mount()
  {
    $this->store = auth()->user()->store;
  }

  public function render()
  {
    // Get both store-specific and master categories
    $categories = WhatsappBotCommandCategory::where('store_id', $this->store->id)
      ->orderBy('display_order', 'asc')
      ->paginate(10);

    return view('livewire.store.whatsapp-bot-command-categories', [
      'categories' => $categories
    ])->layout('components.layouts.app-dashboard');
  }

  /**
   * Reset the form fields
   */
  public function resetFields()
  {
    $this->reset([
      'editingCategory',
      'categoryId',
      'name',
      'description',
      'displayOrder',
      'isActive'
    ]);
    $this->displayOrder = 0;
    $this->isActive = true;
  }

  /**
   * Show the form to create a new category
   */
  public function createCategory()
  {
    $this->resetFields();
    $this->editingCategory = true;
  }

  /**
   * Edit an existing category
   */
  public function editCategory($id)
  {
    $category = WhatsappBotCommandCategory::where('store_id', $this->store->id)
      ->where('id', $id)
      ->firstOrFail();

    $this->categoryId = $category->id;
    $this->name = $category->name;
    $this->description = $category->description;
    $this->displayOrder = $category->display_order;
    $this->isActive = $category->is_active;

    $this->editingCategory = true;
  }

  /**
   * Save a new or existing category
   */
  public function saveCategory()
  {
    $this->validate();

    $data = [
      'name' => $this->name,
      'description' => $this->description,
      'display_order' => $this->displayOrder,
      'is_active' => $this->isActive,
      'store_id' => $this->store->id,
      'is_master' => false // Store users can't create master categories
    ];

    if ($this->categoryId) {
      // Update existing category
      $category = WhatsappBotCommandCategory::where('store_id', $this->store->id)
        ->where('id', $this->categoryId)
        ->first();

      if ($category) {
        $category->update($data);
        session()->flash('success', 'Category updated successfully.');
      } else {
        session()->flash('error', 'You do not have permission to edit this category.');
      }
    } else {
      // Create new category
      WhatsappBotCommandCategory::create($data);
      session()->flash('success', 'Category created successfully.');
    }

    $this->resetFields();
  }

  /**
   * Show delete confirmation modal
   */
  public function confirmDelete($id)
  {
    $category = WhatsappBotCommandCategory::where('store_id', $this->store->id)
      ->where('id', $id)
      ->first();

    if (!$category) {
      session()->flash('error', 'You do not have permission to delete this category.');
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
   * Delete a category
   */
  public function deleteCategory()
  {
    if ($this->deleteId) {
      $category = WhatsappBotCommandCategory::where('store_id', $this->store->id)
        ->where('id', $this->deleteId)
        ->first();

      if (!$category) {
        session()->flash('error', 'You do not have permission to delete this category.');
        $this->deleteId = null;
        $this->showDeleteModal = false;
        return;
      }

      // Check if category is in use
      if ($category->commands()->count() > 0) {
        session()->flash('error', 'Cannot delete category that has commands. Please move or delete the commands first.');
      } else {
        $category->delete();
        session()->flash('success', 'Category deleted successfully.');
      }

      $this->deleteId = null;
      $this->showDeleteModal = false;
    }
  }

  /**
   * Toggle category active status
   */
  public function toggleActive($id)
  {
    $category = WhatsappBotCommandCategory::where('store_id', $this->store->id)
      ->where('id', $id)
      ->first();

    if (!$category) {
      session()->flash('error', 'You do not have permission to update this category.');
      return;
    }

    $category->is_active = !$category->is_active;
    $category->save();

    session()->flash('success', 'Category status updated.');
  }

  /**
   * Show the import master categories modal
   */
  public function showImportModal()
  {
    // Get master categories that haven't been imported yet
    $this->availableMasterCategories = WhatsappBotCommandCategory::where('is_master', true)
      ->whereDoesntHave('commands', function ($query) {
        $query->where('store_id', $this->store->id);
      })
      ->get();

    $this->selectedMasterCategories = [];
    $this->showImportModal = true;
  }

  /**
   * Import selected master categories
   */
  public function importCategories()
  {
    if (empty($this->selectedMasterCategories)) {
      session()->flash('error', 'Please select at least one category to import.');
      return;
    }

    $importedCount = 0;

    foreach ($this->selectedMasterCategories as $categoryId) {
      $masterCategory = WhatsappBotCommandCategory::where('is_master', true)
        ->where('id', $categoryId)
        ->first();

      if ($masterCategory) {
        // Create a store-specific copy of the category
        $storeCategory = WhatsappBotCommandCategory::create([
          'name' => $masterCategory->name,
          'description' => $masterCategory->description,
          'display_order' => $masterCategory->display_order,
          'is_active' => true,
          'is_master' => false,
          'store_id' => $this->store->id
        ]);

        $importedCount++;
      }
    }

    $this->showImportModal = false;
    $this->selectedMasterCategories = [];

    if ($importedCount > 0) {
      session()->flash('success', "Successfully imported {$importedCount} categories.");
    } else {
      session()->flash('error', 'No categories were imported.');
    }
  }

  /**
   * Cancel importing
   */
  public function cancelImport()
  {
    $this->showImportModal = false;
    $this->selectedMasterCategories = [];
  }

  /**
   * Cancel editing/creating
   */
  public function cancel()
  {
    $this->resetFields();
  }
}
