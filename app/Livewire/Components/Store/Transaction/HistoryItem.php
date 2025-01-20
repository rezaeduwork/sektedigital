<?php

namespace App\Livewire\Components\Store\Transaction;

use Livewire\Component;
use Livewire\Attributes\On;

class HistoryItem extends Component
{
  public $tx;
  public $showOthers = false;
  public $status;
  public $checkedIds = [];
  public function mount($tx, $status)
  {
    $this->tx = $tx;
    $this->status = $status;
  }
  public function toggleShowProduct()
  {
    $this->showOthers = !$this->showOthers;
  }
  #[On('toggle-check.{tx.id}')]
  public function toggleCheck($id)
  {
    if (in_array($id, $this->checkedIds)) {
      // Remove the ID if it's already in the array
      $this->checkedIds = array_filter($this->checkedIds, fn($checkedId) => $checkedId !== $id);
    } else {
      // Add the ID if it's not in the array
      $this->checkedIds[] = $id;
    }
  }
  public function reloadParent()
  {
    $this->dispatch('reload')->to(\App\Livewire\Store\Transaction\History::class);
    $this->dispatch('reload')->to(\App\Livewire\Components\Store\Transaction\HistoryTable::class);
  }
  public function processing()
  {
    $confirmed = $this->tx->storeDetails()->whereIn('status', ['confirmed'])->first();
    if (!$confirmed) {
      return;
    }
    // Add your logic to handle the acceptance of the transaction here
    $storeTx = $this->tx->storeDetails()->whereIn('status', ['confirmed'])->whereIn('id', $this->checkedIds)->get();
    foreach ($storeTx as $row) {
      $row->status = 'processed';
      $row->save();
      transactionActivity($this->tx, auth()->id(), 'processed', ('processing by seller'), 'detail', $row->id);
    }
    $this->dispatch('alert-success', message: 'Berhasil Proses Pesanan!');
    $this->dispatch('close-confirmation');
    $this->reloadParent();
  }
  public function completing()
  {
    $processed = $this->tx->storeDetails()->whereIn('status', ['processed'])->first();
    if (!$processed) {
      return;
    }
    // Add your logic to handle the acceptance of the transaction here
    $storeTx = $this->tx->storeDetails()->whereIn('status', ['processed'])->whereIn('id', $this->checkedIds)->get();
    foreach ($storeTx as $row) {
      $row->status = 'store_finished';
      $row->save();
      transactionActivity($this->tx, auth()->id(), 'store_finished', ('finished by seller'), 'detail', $row->id);
    }
    $this->dispatch('alert-success', message: 'Berhasil Menyelesaikan Pesanan!');
    $this->dispatch('close-confirmation');
    $this->reloadParent();
  }
  public function render()
  {
    return view('livewire.components.store.transaction.history-item');
  }
}
