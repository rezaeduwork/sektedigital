<?php

namespace App\Livewire\Admin\Withdrawal;

use Livewire\Component;

class ModalRejected extends Component
{
  public $search;
  public $perPage = 10;
  public $total = 0;
  public function loadMore()
  {
    $this->perPage += 10;
  }
  public function render()
  {
    $list = \App\Models\UserBalance::query()->where('type', 'withdraw')->whereStatus('rejected');
    if ($this->search) {
      $list = $list->where('id', 'like', '%' . $this->search . '%');
    }
    $list = $list->take($this->perPage)->get();
    return view('livewire.admin.withdrawal.modal-rejected', compact('list'));
  }
}
