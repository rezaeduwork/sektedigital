<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;

class Restore extends Component
{
  public $product;
  public function mount($id)
  {
    $this->product = \App\Models\Product::onlyTrashed()->find($id);
  }
  public function restore()
  {
    $this->product->restore();
    $this->dispatch('alert-success', ['message' => 'Product restored successfully']);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Product::class);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Product\ModalDeleted::class);
  }
  public function render()
  {
    return view('livewire.admin.product.restore');
  }
}
