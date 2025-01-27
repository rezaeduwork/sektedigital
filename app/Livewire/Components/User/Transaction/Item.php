<?php

namespace App\Livewire\Components\User\Transaction;

use Livewire\Component;

class Item extends Component
{
  public $tx;
  public $complainText;
  public $status;
  public $showOthers = false;
  public function mount($tx, $status)
  {
    $this->tx = $tx;
    $this->status = $status;
  }
  public function toggleShowProduct()
  {
    $this->showOthers = !$this->showOthers;
  }
  public function complain()
  {
    if (!trim($this->complainText)) {
      $this->dispatch('alert-error', message: 'Deskripsi tidak boleh kosong!');
      return;
    } else if (strlen(trim($this->complainText)) < 50) {
      $this->dispatch('alert-error', message: 'Keterangan terlalu sedikit, Minimal 50 karakter!');
      return;
    }
    $this->tx->status = 'complain';
    $this->tx->save();
    transactionActivity($this->tx, auth()->id(), 'complain', $this->complainText);
    $this->dispatch('alert-success', message: 'Pesananmu berhasil di komplain! Silahkan tunggu seller memproses komplainmu.');
    $this->dispatch('reload')->to(\App\Livewire\User\Transaction::class);
  }
  public function finish()
  {
    $this->tx->status = 'finished';
    $this->tx->save();
    $this->dispatch('alert-success', message: 'Yeay, Pesanan kamu berhasil di selesaikan!');
    return $this->redirect(url('user/transaction') . '?tab=finished', navigate: true);
  }
  public function render()
  {
    return view('livewire.components.user.transaction.item');
  }
}
