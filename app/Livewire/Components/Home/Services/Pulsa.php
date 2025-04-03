<?php

namespace App\Livewire\Components\Home\Services;

use Livewire\Component;

class Pulsa extends Component
{
  public $provider;
  public $providers;
  public $phone;
  public $amount;
  public $product;
  public $products = [];
  public $validated = false;
  public function mount()
  {
    $this->providers = \App\Models\ProductInstant::where('category', 'Pulsa')->where('status', 'active')->groupBy('brand')->select('brand')->get()->pluck('brand');
  }
  public function updated($prop)
  {
    if (in_array($prop, ['phone', 'provider', 'amount'])) {
      $this->validated = false;
      if (in_array($prop, ['phone', 'provider'])) {
        if ($this->provider && $this->phone && strlen($this->phone) > 5) {
          $this->products = \App\Models\ProductInstant::where('category', 'Pulsa')->where('status', 'active')->whereBrand($this->provider)->orderBy('price')->get();
        }
      }
    }
    $this->checkValidatedProps();
  }
  private function checkValidatedProps($shouldAlert = false)
  {
    if ($this->phone && !preg_match('/^(08|62)/', $this->phone)) {
      if ($shouldAlert) {
        $this->addError('phone', 'Nomor HP harus dimulai dengan 08 atau 62!');
      }
      return false;
    }
    if ($this->provider && $this->phone && strlen($this->phone) > 5) {
    } else {
      return false;
    }
    if (!$this->provider) {
      return false;
    }
    if ($this->amount == null) {
      return false;
    }
    $this->product = \App\Models\ProductInstant::find($this->amount);
    if (!$this->product) {
      return false;
    }
    $this->validated = true;
  }
  public function render()
  {
    return view('livewire.components.home.services.pulsa');
  }
}
