<?php

namespace App\Livewire\Components\ProductDetail;

use Livewire\Component;

class Description extends Component
{
  public $product;
  public function mount($product) {
    $this->product = $product;
  }
  public function render()
  {
    return view('livewire.components.product-detail.description');
  }
}
