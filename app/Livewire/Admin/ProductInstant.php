<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;

class ProductInstant extends Component
{
  use WithPagination, WithoutUrlPagination;
  public $search;
  public $searchCode;
  public $searchCategory;
  public $filterStatus;
  public $emptyStock;
  public $sort;
  public function updated($prop)
  {
    if (in_array($prop, ['search', 'searchCode', 'searchCategory'])) {
      $this->resetPage();
    }
  }
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\ProductInstant::query();
    if ($this->search) {
      $list->where(function ($query) {
        $query->where('title', 'like', '%' . $this->search . '%')
          ->orWhere('brand', 'like', '%' . $this->search . '%')
          ->orWhere('highlight', 'like', '%' . $this->search . '%')
          ->orWhere('description', 'like', '%' . $this->search . '%');
      });
    }
    if ($this->searchCode) {
      $list->where(function ($query) {
        $query->where('code', 'like', '%' . $this->searchCode . '%');
      });
    }
    if ($this->searchCategory) {
      $list->where(function ($query) {
        $query->where('category', 'like', '%' . $this->searchCategory . '%');
      });
    }
    if ($this->filterStatus !== null) {
      $list->where(function ($query) {
        $query->where('status', 'like', '%' . $this->filterStatus . '%');
      });
    }
    if ($this->emptyStock == 'Habis') {
      $list->where(function ($query) {
        $query->where('stock', 0);
      });
    } else {
      $list->where(function ($query) {
        $query->where('stock', '<>', 0);
      });
    }
    if (!$this->sort) {
      $list->latest();
    }
    $list = $list->simplePaginate(10);
    return view('livewire.admin.product-instant', compact('list'))->layout('components.layouts.app-admin');
  }
}
