<?php

namespace App\Livewire\Components\Store\Transaction;

use Livewire\Component;
use Livewire\Attributes\On;

class HistoryItemDetail extends Component
{
  public $detail;
  public $isSelected = false;
  public function mount($detail, $isSelected)
  {
    $this->detail = $detail;
    $this->isSelected = $isSelected;
  }
  public function select($id)
  {
    $this->reset('isSelected');
    $this->dispatch('toggle-check.' . $this->detail->transaction->id, id: $id)->to(\App\Livewire\Components\Store\Transaction\HistoryItem::class);
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.components.store.transaction.history-item-detail');
  }
}
