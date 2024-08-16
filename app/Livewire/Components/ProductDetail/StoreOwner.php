<?php

namespace App\Livewire\Components\ProductDetail;

use Livewire\Component;

class StoreOwner extends Component
{
  public $product;
  public $store;
  public function mount($product) {
    $this->product = $product;
    $this->store = $product->store;
  }
  public function render()
  {
    return view('livewire.components.product-detail.store-owner');
  }
}
