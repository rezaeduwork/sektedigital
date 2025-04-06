<?php

namespace App\Livewire\ProductInstant;

use Livewire\Component;
use Livewire\Attributes\Url;

class GameInput extends Component
{
  public $product;
  #[Url]
  public ?string $provider;
  #[Url]
  public ?string $account;
  #[Url]
  public ?string $productId;
  public $amount = 0;
  public function mount($product)
  {
    $this->product = $product;
    $this->amount = $product->price;
  }
  public function render()
  {
    return view('livewire.product-instant.game-input');
  }
}
