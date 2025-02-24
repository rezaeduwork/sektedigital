<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;

class Delete extends Component
{
  public $product;
  public function mount($id)
  {
    $this->product = \App\Models\Product::find($id);
  }
  public function delete()
  {
    $this->product->delete();
    $this->dispatch('alert-success', ['message' => 'Product deleted successfully']);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Product::class);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Product\ModalDeleted::class);
  }
  public function render()
  {
    return view('livewire.admin.product.delete');
  }
}
