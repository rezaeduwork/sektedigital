<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;

class Restore extends Component
{
  public $category;
  public function mount($id)
  {
    $this->category = \App\Models\CategoryProduct::onlyTrashed()->find($id);
  }
  public function restore()
  {
    $this->category->restore();
    $this->dispatch('alert-success', ['message' => 'Category restored successfully']);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Category::class);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Category\ModalDeleted::class);
  }
  public function render()
  {
    return view('livewire.admin.category.restore');
  }
}
