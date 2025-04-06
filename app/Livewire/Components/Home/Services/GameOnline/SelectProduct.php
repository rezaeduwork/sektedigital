<?php

namespace App\Livewire\Components\Home\Services\GameOnline;

use Livewire\Component;

class SelectProduct extends Component
{
  public $brand;
  public $product;
  public $informations = [];
  public $account = null;
  public function mount($brand)
  {
    $this->brand = $brand;
  }
  public function selectProduct($productId)
  {
    $this->product = \App\Models\ProductInstant::find($productId);
    $this->informations = [
      [
        'label' => 'Player ID',
        'type' => 'number',
        'value' => null,
      ]
    ];
    if ($this->brand == 'Mobile Legends') {
      $this->informations = [
        [
          'label' => 'User ID',
          'type' => 'number',
          'value' => null,
        ],
        [
          'label' => 'Zone ID',
          'type' => 'number',
          'value' => null,
        ]
      ];
    }
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
  }
  public function pay()
  {
    return $this->redirect('i/' . $this->product->code . '?provider=' . $this->brand . '&account=' . $this->account, navigate: true);
  }
  public function render()
  {
    $list = \App\Models\ProductInstant::where('category', 'Games')->where('brand', $this->brand)->orderBy('price')->get();
    return view('livewire.components.home.services.game-online.select-product', compact('list'));
  }
}
