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
    $price = 0;
    $apiKey = config('services.crypto.bsc.api_key');
    $url = "https://api.bscscan.com/api?module=stats&action=bnbprice&apikey={$apiKey}";

    $response = Http::get($url);

    if ($response->successful()) {
      $data = $response->json();
      $price = round($data['result']['ethusd'], 1);
    }
    $priceIdr = \App\Models\Currency::where('symbol', 'usdidr')->first();
    if ($priceIdr) {
      $this->price = $price * $priceIdr->price;
      // $this->price = null;
    }
  }
  public function render()
  {
    return view('livewire.product-instant.crypto-live-price');
  }
}
