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
    if (in_array($this->product->category, ['Games'])) {
      $this->informations = [
        [
          'label' => 'Game',
          'name' => 'provider',
          'value' => $this->provider,
        ],
        [
          'label' => 'Account ID',
          'name' => 'account_id',
          'value' => $this->account_id,
        ],
        [
          'label' => 'Zone ID',
          'name' => 'zone_id',
          'value' => $this->zone_id,
        ],
        [
          'label' => 'Email',
          'name' => 'email',
          'value' => $this->email,
        ],
      ];
    } else if (in_array($this->product->category, ['Pulsa', 'Data'])) {
      $this->informations = [
        [
          'label' => 'Provider',
          'name' => 'provider',
          'value' => $this->provider,
        ],
        [
          'label' => 'Nomor HP',
          'name' => 'phone',
          'value' => $this->phone,
        ],
      ];
    }
  }
  public function render()
  {
    return view('livewire.product-instant');
  }
}
