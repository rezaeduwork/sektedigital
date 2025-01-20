<?php

namespace App\Livewire\Components\Store\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class HistoryTable extends Component
{
  use WithPagination;
  public $status;
  public function mount($status)
  {
    $this->status = $status;
  }
  #[On('reload')]
  public function render()
  {
    // dd(auth()->user()->store->id);
    $list = \App\Models\Transaction::query()->storeTransactionQuery($this->status);
    $list = $list->latest()->paginate(10);
    return view('livewire.components.store.transaction.history-table', compact('list'));
  }
}
