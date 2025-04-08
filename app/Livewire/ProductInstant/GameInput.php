<?php

namespace App\Livewire\ProductInstant;

use Livewire\Component;
use Livewire\Attributes\Url;

class GameInput extends Component
{
  public $product;
  public $informations = [];
  public $amount = 0;
  public function mount($product, $informations)
  {
    $this->product = $product;
    $this->amount = $product->price;
    $this->informations = $informations;
  }
  public function rendered()
  {
    $this->dispatch('fill-infomations', informations: $this->informations)->to(\App\Livewire\ProductInstant\PaymentInfo::class);
  }
  public function render()
  {
    return view('livewire.product-instant.game-input');
  }
}
