<?php

namespace App\Livewire\Components\Home\Services\GameOnline;

use Livewire\Component;

class SelectProduct extends Component
{
  public $brand;
  public $product;
  public $informations = [];
  public $account = null;
  public $canSubmit = false;
  public function mount($brand)
  {
    $this->brand = $brand;
  }
  public function selectProduct($productId)
  {
    $this->product = \App\Models\ProductInstant::find($productId);
    $this->informations = getTransactionInstantInformations($this->product->category, $this->brand);
  }
  public function fillAccount($index, $value)
  {
    $this->informations[$index]['value'] = $value;
    $this->reloadAccount();
  }
  public function reloadAccount()
  {
    $infomationValues = collect($this->informations)->pluck('value')->toArray();
    if (sizeof($infomationValues) > 0) {
      $this->account = implode('', $infomationValues);
    } else {
      $this->account = null;
    }
    if ($this->product && $this->account) {
      $checkHasNullValue = collect($this->informations)->contains(function ($item) {
        return $item['value'] == null && ((isset($item['required']) && $item['required'] !== false) || !isset($item['required']));
      });
      if ($checkHasNullValue) {
        $this->canSubmit = false;
      } else {
        $this->canSubmit = true;
      }
    } else {
      $this->canSubmit = false;
    }
  }
  public function pay()
  {
    if (!$this->canSubmit) {
      $this->dispatch('alert-error', message: 'Lengkapi semua data yang dibutuhkan');
      return;
    }
    $informationQuery = collect($this->informations)->map(function ($item) {
      return $item['name'] . '=' . $item['value'];
    })->implode('&');
    return $this->redirect('i/' . $this->product->code . '?provider=' . $this->brand . '&' . $informationQuery, navigate: true);
  }
  public function render()
  {
    $list = \App\Models\ProductInstant::where('category', 'Games')->where('brand', $this->brand)->orderByRaw('type desc,price asc')->get();
    return view('livewire.components.home.services.game-online.select-product', compact('list'));
  }
}
