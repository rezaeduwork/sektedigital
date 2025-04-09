<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;

class ProductInstant extends Component
{
  public $product;
  #[Url]
  public ?string $provider;
  #[Url]
  public ?string $account_id;
  #[Url]
  public ?string $zone_id;
  #[Url]
  public ?string $productId;
  #[Url]
  public ?string $email;
  #[Url]
  public ?string $phone;
  public $informations = [];
  public function mount($code)
  {
    $product = \App\Models\ProductInstant::where('code', $code)->first();
    if ($product) {
      $this->product = $product;
    } else {
      abort(404);
    }
    $this->fillInformations();
  }
  public function fillInformations()
  {
    $this->informations = getTransactionInstantInformations($this->product->category, $this->provider, [
      'provider' => $this->provider ?? null,
      'account_id' => $this->account_id ?? null,
      'zone_id' => $this->zone_id ?? null,
      'email' => $this->email ?? null,
      'phone' => $this->phone ?? null,
    ]);
  }
  public function render()
  {
    return view('livewire.product-instant');
  }
}
