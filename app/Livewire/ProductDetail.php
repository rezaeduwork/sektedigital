<?php

namespace App\Livewire;

use Livewire\Component;

class ProductDetail extends Component
{
  public $product;
  public function mount($slug)
  {
    $this->product = \App\Models\Product::whereSlug($slug)->firstOrFail();
    productActivity($this->product, 'view', auth()->id() ?? -1);
  }
  public function render()
  {
    return view('livewire.product-detail');
  }
}
