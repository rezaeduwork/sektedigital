<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;

class Delete extends Component
{
  public $category;
  public function mount($id)
  {
    $this->category = \App\Models\CategoryProduct::find($id);
  }
  public function delete()
  {
    $this->category->delete();
    $this->dispatch('alert-success', ['message' => 'Category deleted successfully']);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Category::class);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Category\ModalDeleted::class);
  }
  public function render()
  {
    return view('livewire.admin.category.delete');
  }
}
