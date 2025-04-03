<?php

namespace App\Livewire;

use Livewire\Component;

class ProductInstant extends Component
{
  public $product;
  public function mount($code)
  {
    $product = \App\Models\ProductInstant::where('code', $code)->first();
    if ($product) {
      $this->product = $product;
    } else {
      abort(404);
    }
  }
  public function render()
  {
    return view('livewire.product-instant');
  }
}
