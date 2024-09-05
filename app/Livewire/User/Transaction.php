<?php

namespace App\Livewire\User;

use Livewire\Component;

class Transaction extends Component
{
  public $activeTab = 'Semua';

  public function render()
  {
    $transactions = queryListUserTransaction($this->activeTab);

    $transactions = $transactions->latest()->get();
    return view('livewire.user.transaction',compact('transactions'));
  }
}
