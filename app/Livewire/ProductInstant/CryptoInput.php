<?php

namespace App\Livewire\ProductInstant;

use Livewire\Component;

class CryptoInput extends Component
{
  public $product;
  public $address;
  public $amount;
  public function mount($product)
  {
    $this->product = $product;
  }
  public function updatedAmount()
  {
    $this->dispatch('reload-crypto-payment.' . $this->product->id, amount: $this->amount, address: $this->address)->to(\App\Livewire\ProductInstant\PaymentInfo::class);
  }
  public function updatedAddress()
  {
    $this->dispatch('reload-crypto-payment.' . $this->product->id, amount: $this->amount, address: $this->address)->to(\App\Livewire\ProductInstant\PaymentInfo::class);
  }
  public function render()
  {
    return view('livewire.product-instant.crypto-input');
  }
}
