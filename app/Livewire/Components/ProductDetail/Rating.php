<?php

namespace App\Livewire\Components\ProductDetail;

use Livewire\Component;

class Rating extends Component
{
  public $product;
  public function mount($product) {
    $this->product = $product;
  }
  public function render()
  {
    return view('livewire.components.product-detail.rating');
  }
}
