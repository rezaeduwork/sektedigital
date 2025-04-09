<?php

namespace App\Livewire\Admin\TransactionInstant;

use Livewire\Component;
use Livewire\Attributes\On;

class ProcessManualAction extends Component
{
  public $transaction;
  public function mount($transaction)
  {
    $this->transaction = $transaction;
  }
  public function process()
  {
    $data = digiflazz()->createTransaction($this->transaction);
    if ($data['success'] === true) {
      $this->transaction->update(['status' => 'finished']);
    } else {
      if ($data['data']['status'] == 'Pending') {
        $this->transaction->update(['status' => 'confirmed']);
      } else {
        $this->transaction->update(['status' => 'rejected']);
      }
    }
    // $this->transaction->update(['status' => 'processed']);
    $this->dispatch('reload')->to(\App\Livewire\Admin\TransactionInstant::class);
    $this->dispatch('reload')->self();
    if ($this->transaction->status == 'finished') {
      $this->dispatch('alert-success', message: 'Transaksi berhasil.');
    } else if ($this->transaction->status == 'rejected') {
      $this->dispatch('alert-error', message: 'Transaksi gagal');
    } else {
      $this->dispatch('alert-success', message: 'Transaksi pending.');
    }
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.admin.transaction-instant.process-manual-action');
  }
}
