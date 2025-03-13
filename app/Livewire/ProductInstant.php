<?php

namespace App\Livewire;

use Livewire\Component;

class ProductInstant extends Component
{
  public $product;
  public function mount($code)
  {
    $product = collect(config('product'))->firstWhere('code', $code);
    if ($product) {
      $this->product = (object)$product;
    } else {
      abort(404);
    }
  }
  public function render()
  {
    return view('livewire.product-instant');
  }
}
