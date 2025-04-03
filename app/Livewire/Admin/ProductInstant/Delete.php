<?php

namespace App\Livewire\Admin\ProductInstant;

use Livewire\Component;

class Delete extends Component
{
  public $product;
  public function mount($id)
  {
    $this->product = \App\Models\ProductInstant::find($id);
  }
  public function delete()
  {
    $this->product->delete();
    $this->dispatch('alert-success', ['message' => 'Product deleted successfully']);
    $this->dispatch('reload')->to(\App\Livewire\Admin\ProductInstant::class);
  }
  public function render()
  {
    return view('livewire.admin.product-instant.delete');
  }
}
