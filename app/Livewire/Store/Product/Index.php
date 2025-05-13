<?php

namespace App\Livewire\Store\Product;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
  use WithPagination;
  public $tab = 'Semua';
  public $name;
  public $category_id;
  public $sort;
  public function render()
  {
    $list = \App\Models\Product::query();
    if ($this->tab == 'Tidak Aktif') {
      $list->whereStatus('inactive');
    }
    if ($this->name) {
      $list->where('title', 'like', '%' . $this->name . '%');
    }
    if ($this->category_id) {
      $list->where('category_product_id', $this->category_id);
    }
    if ($this->sort == 'newest') {
      $list->orderBy('created_at', 'desc');
    } elseif ($this->sort == 'oldest') {
      $list->orderBy('created_at', 'asc');
    } else {
      $list->orderBy('created_at', 'desc');
    }
    $list = $list->paginate(10);
    return view('livewire.store.product.index', compact('list'))->layout('components.layouts.app-dashboard');
  }
}
