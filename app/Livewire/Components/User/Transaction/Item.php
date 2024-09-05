<?php

namespace App\Livewire\Components\User\Transaction;

use Livewire\Component;

class Item extends Component
{
  public $tx;
  public function mount($tx) {
    $this->tx = $tx;
  }
  public function render()
  {
    return view('livewire.components.user.transaction.item');
  }
}
