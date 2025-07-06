<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\WhatsappBotCommandCategory;
use Livewire\WithPagination;

class WhatsappCommandCategories extends Component
{
  use WithPagination;

  public $editingCategory = false;
  public $categoryId;
  public $name;
  public $description;
  public $displayOrder = 0;
  public $isActive = true;
  public $isMaster = false;

  // For confirmation modal
  public $showDeleteModal = false;
  public $deleteId = null;

  protected $rules = [
    'name' => 'required|string|max:100',
    'description' => 'nullable|string|max:255',
    'displayOrder' => 'required|integer|min:0',
    'isActive' => 'boolean',
    'isMaster' => 'boolean'
  ];

  public function render()
  {
    $categories = WhatsappBotCommandCategory::orderBy('display_order', 'asc')
      ->paginate(10);

    return view('livewire.admin.whatsapp-command-categories', [
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
      'isActive',
      'isMaster'
    ]);
    $this->displayOrder = 0;
    $this->isActive = true;
    $this->isMaster = false;
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
    $category = WhatsappBotCommandCategory::findOrFail($id);

    $this->categoryId = $category->id;
    $this->name = $category->name;
    $this->description = $category->description;
    $this->displayOrder = $category->display_order;
    $this->isActive = $category->is_active;
    $this->isMaster = $category->is_master;

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
      'is_master' => $this->isMaster
    ];

    if ($this->categoryId) {
      // Update existing category
      $category = WhatsappBotCommandCategory::find($this->categoryId);
      $category->update($data);
      session()->flash('success', 'Category updated successfully.');
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
      $category = WhatsappBotCommandCategory::find($this->deleteId);

      // Check if category is in use
      if ($category && $category->commands()->count() > 0) {
        session()->flash('error', 'Cannot delete category that has commands. Please move or delete the commands first.');
      } else if ($category) {
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
    $category = WhatsappBotCommandCategory::findOrFail($id);
    $category->is_active = !$category->is_active;
    $category->save();

    session()->flash('success', 'Category status updated.');
  }

  /**
   * Cancel editing/creating
   */
  public function cancel()
  {
    $this->resetFields();
  }
}
