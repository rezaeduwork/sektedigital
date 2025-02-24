<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Category extends Component
{
  use WithPagination;
  public $search;
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\CategoryProduct::query();
    if ($this->search) {
      $list->where('name', 'like', '%' . $this->search . '%');
    }
    $list = $list->paginate(10);
    return view('livewire.admin.category', compact('list'))->layout('components.layouts.app-admin');
  }
}
