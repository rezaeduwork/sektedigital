<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Product extends Component
{
  use WithPagination;
  public $search;
  public $searchStore;
  public $sort;
  public function render()
  {
    $list = \App\Models\Product::query();
    if ($this->search) {
      $list->where(function ($query) {
        $query->where('title', 'like', '%' . $this->search . '%')->orWhere('highlight', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%');
      });
    }
    if ($this->searchStore) {
      $list->whereHas('store', function ($query) {
        $query->where('name', 'like', '%' . $this->searchStore . '%');
      });
    }
    if (!$this->sort) {
      $list->latest();
    }
    $list = $list->simplePaginate(10);
    return view('livewire.admin.product', compact('list'))->layout('components.layouts.app-admin');
  }
}
