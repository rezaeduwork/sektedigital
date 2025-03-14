<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProductInstant extends Component
{
  use WithPagination;
  public $search;
  public $searchStore;
  public $sort;
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\ProductInstant::query();
    if ($this->search) {
      $list->where(function ($query) {
        $query->where('title', 'like', '%' . $this->search . '%')->orWhere('highlight', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%');
      });
    }
    if (!$this->sort) {
      $list->latest();
    }
    $list = $list->simplePaginate(10);
    return view('livewire.admin.product-instant', compact('list'))->layout('components.layouts.app-admin');
  }
}
