<?php

namespace App\Livewire\Admin\Withdrawal;

use Livewire\Component;

class ActionRejectWithdrawal extends Component
{
  public $history;
  public function mount($history)
  {
    $this->history = $history;
  }
  public function render()
  {
    return view('livewire.admin.withdrawal.action-reject-withdrawal');
  }
}
