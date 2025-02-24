<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use Livewire\Attributes\On;

class ModalDeleted extends Component
{
  public $search;
  public $perPage = 10;
  public $total = 0;
  public function mount()
  {
    $this->total = \App\Models\Product::onlyTrashed()->count();
  }
  public function loadMore()
  {
    $this->perPage += 10;
  }
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\Product::query();
    if ($this->search) {
      $list = $list->where('name', 'like', '%' . $this->search . '%');
    }
    $list = $list->onlyTrashed()->take($this->perPage)->get();
    return view('livewire.admin.product.modal-deleted', compact('list'));
  }
}
