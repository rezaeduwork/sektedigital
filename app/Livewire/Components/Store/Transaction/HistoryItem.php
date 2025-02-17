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
    $confirmed = $this->tx->status == 'confirmed';
    if (!$confirmed) {
      return;
    }
    $this->tx->status = 'processed';
    $this->tx->save();
    transactionActivity($this->tx, auth()->id(), 'processed', ('processing by seller'));
    $this->tx->user->notify(new \App\Notifications\TransactionNotification($this->tx, 'Transaksi proses', 'Pesananmu diproses seller, silahkan ditunggu 🙏'));
    $this->dispatch('alert-success', message: 'Berhasil Proses Pesanan!');
    $this->dispatch('close-confirmation');
    $this->reloadParent();
  }
  #[On('completing.{tx.id}')]
  public function completing()
  {
    $processed = $this->tx->status == 'processed';
    if (!$processed) {
      return;
    }
    $this->tx->status = 'store_finished';
    $this->tx->save();
    transactionActivity($this->tx, auth()->id(), 'store_finished', ('finished by seller'));
    $this->tx->user->notify(new \App\Notifications\TransactionNotification($this->tx, 'Transaksi selesai', 'Pesananmu diselesaikan seller, silahkan konfirmasi di menu transaksi 🥳'));
    $this->dispatch('alert-success', message: 'Berhasil Menyelesaikan Pesanan!');
    $this->dispatch('close-confirmation');
    $this->reloadParent();
  }
  public function rejectComplain()
  {
    $complain = $this->tx->status == 'complain';
    if (!$complain) {
      return;
    }
    $this->tx->status = 'finished';
    $this->tx->save();
    transactionActivity($this->tx, auth()->id(), 'finished', ('complain rejected by seller'));
    $this->tx->user->notify(new \App\Notifications\TransactionNotification($this->tx, 'Transaksi dikomplain', 'Complainmu ditolak seller! 😔'));
    $this->dispatch('alert-success', message: 'Berhasil menyelesaikan pesanan!');
    $this->dispatch('close-confirmation');
    $this->reloadParent();
  }
  public function acceptComplain()
  {
    $complain = $this->tx->status == 'complain';
    if (!$complain) {
      return;
    }
    $this->tx->status = 'finished';
    $this->tx->save();
    transactionActivity($this->tx, auth()->id(), 'finished', ('complain accepted by seller'));
    $this->tx->user->notify(new \App\Notifications\TransactionNotification($this->tx, 'Transaksi dikomplain', 'Complainmu diterima seller! Dana akan di kembalikan.'));
    $this->dispatch('alert-success', message: 'Berhasil menyelesaikan pesanan!');
    $this->dispatch('close-confirmation');
    $this->reloadParent();
  }
  public function render()
  {
    return view('livewire.components.store.transaction.history-item');
  }
}
