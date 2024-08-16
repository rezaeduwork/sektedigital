<?php

namespace App\Livewire\Components\ProductDetail;

use Livewire\Component;

class StoreProducts extends Component
{
  public $store;
  public function mount($store) {
    $this->store = $store;
  }
  public function render()
  {
    return view('livewire.components.product-detail.store-products');
  }
}
