<?php

namespace App\Livewire;

use Livewire\Component;

class ProductDetail extends Component
{
  public $product;
  public function mount($slug) {
    $this->product = \App\Models\Product::whereSlug($slug)->firstOrFail();
  }
  public function render()
  {
    return view('livewire.product-detail');
  }
}
