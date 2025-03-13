<?php

namespace App\Livewire\ProductInstant;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CryptoLivePrice extends Component
{
  public $ticker;
  public $price;
  public function mount($ticker)
  {
    $this->ticker = $ticker;
    $this->getPrice();
  }
  public function getPrice()
  {
    \App\Models\Currency::reloadRate('bnbidr');
    $bnbidr = \App\Models\Currency::where('symbol', 'bnbidr')->first();
    if ($bnbidr) {
      $this->price = $bnbidr->price;
    }
  }
  public function render()
  {
    return view('livewire.product-instant.crypto-live-price');
  }
}
