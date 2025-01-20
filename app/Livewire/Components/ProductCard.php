<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ProductCard extends Component
{
  public $product;
  public function mount($product)
  {
    $this->product = $product;
  }
  public function render()
  {
    return view('livewire.components.product-card');
  }
}
