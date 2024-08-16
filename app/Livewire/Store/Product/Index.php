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
  public function render()
  {
    $list = \App\Models\Product::query();
    if ($this->tab == 'Tidak Aktif') {
      $list->whereStatus('inactive');
    }
    if ($this->name) {
      $list->where('title', 'like', '%'.$this->name.'%');
    }
    $list = $list->paginate(10);
    return view('livewire.store.product.index', compact('list'))->layout('components.layouts.app-dashboard');
  }
}
