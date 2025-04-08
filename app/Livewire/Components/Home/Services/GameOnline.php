<?php

namespace App\Livewire\Components\Home\Services;

use Livewire\Component;
use Livewire\Attributes\On;

class GameOnline extends Component
{
  public $brand;
  public $search;
  public function changeProductSelected($brand)
  {
    $this->brand = $brand;
  }
  #[On('goBack')]
  public function goBack()
  {
    $this->brand = null;
  }
  public function render()
  {
    $list = \App\Models\ProductInstant::select('brand')->where('category', 'Games');
    if ($this->search) {
      $list->where('brand', 'like', '%' . $this->search . '%');
    }
    $list = $list->groupBy('brand')->get();
    return view('livewire.components.home.services.game-online', compact('list'));
  }
}
