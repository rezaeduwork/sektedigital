<?php

namespace App\Livewire\Components\User\Transaction;

use Livewire\Component;

class Item extends Component
{
  public $tx;
  public $status;
  public $showOthers = false;
  public function mount($tx, $status)
  {
    $this->tx = $tx;
    $this->status = $status;
  }
  public function toggleShowProduct()
  {
    $this->showOthers = !$this->showOthers;
  }
  public function render()
  {
    return view('livewire.components.user.transaction.item');
  }
}
