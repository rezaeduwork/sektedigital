<?php

namespace App\Livewire\Components\Home\Services;

use Livewire\Component;
use Livewire\Attributes\On;

class GameOnline extends Component
{
  public $brand;
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
    return view('livewire.components.home.services.game-online');
  }
}
