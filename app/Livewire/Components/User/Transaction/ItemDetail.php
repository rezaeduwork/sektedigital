<?php

namespace App\Livewire\Components\User\Transaction;

use Livewire\Component;

class ItemDetail extends Component
{
  public $detail;
  public function mount($detail)
  {
    $this->detail = $detail;
  }
  public function render()
  {
    return view('livewire.components.user.transaction.item-detail');
  }
}
