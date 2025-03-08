<?php

namespace App\Livewire\Admin\Withdrawal;

use Livewire\Component;
use Livewire\Attributes\On;

class ActionPendingWithdrawal extends Component
{
  public $history;
  public $note;
  public function mount($history)
  {
    $this->history = $history;
  }
  public function approve($id)
  {
    \App\Models\UserBalance::find($id)->update(['status' => 'success']);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Withdrawal::class);
    $this->dispatch('reload')->self();
    $this->dispatch('alert-success', message: "Berhasil approve withdrawal!");
  }
  public function reject($id)
  {
    $this->history->status = 'rejected';
    $data = json_decode($this->history->data, true);
    if ($data) {
      $data['note'] = $this->note;
    } else {
      $data = json_encode(['note' => $this->note]);
    }
    $this->history->data = $data;
    $this->history->save();
    $this->dispatch('reload')->to(\App\Livewire\Admin\Withdrawal::class);
    $this->dispatch('reload')->self();
    $this->dispatch('alert-success', message: "Berhasil reject withdrawal!");
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.admin.withdrawal.action-pending-withdrawal');
  }
}
