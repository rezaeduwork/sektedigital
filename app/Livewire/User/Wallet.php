<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;

class Wallet extends Component
{
  public $page;
  public $ref_id;
  public $amount;
  public $minWithdraw = 25000;
  public function updatedAmount()
  {
    if ($this->amount > auth()->user()->balance) {
      $this->amount = auth()->user()->balance;
    }
  }
  public function withdraw()
  {
    if (!$this->ref_id) {
      $this->dispatch('alert-error', message: "Gagal, silahkan pilih rekening penarikan.");
      return;
    }
    if ($this->amount === null || !is_numeric($this->amount) || $this->amount < 0) {
      $this->dispatch('alert-error', message: "Gagal, silahkan isi jumlah penarikan.");
      return;
    }
    if ($this->amount < $this->minWithdraw) {
      $this->dispatch('alert-error', message: "Gagal, minimal penarikan " . number_format($this->minWithdraw) . ".");
      return;
    }
    if ($this->amount > auth()->user()->balance) {
      $this->dispatch('alert-error', message: "Gagal, jumlah penarikan melebihi batas.");
      return;
    }
    auth()->user()->balances()->create([
      'uid' => auth()->id() . uniqid(),
      'type' => 'withdraw',
      'amount' => -abs($this->amount),
      'description' => 'Request withdrawal pada ' . now()->translatedFormat('l, d F Y H:i'),
      'status' => 'pending',
      'name' => 'Withdraw Fund'
    ]);
    auth()->user()->reloadBalance();
    $this->dispatch('alert-success', message: "Berhasil request penarikan silahkan tunggu 3x24 jam untuk di proses!");
    $this->dispatch('reload')->to(\App\Livewire\User\Wallet\History::class);
    $this->resetWithdrawalAttr();
  }
  public function resetWithdrawalAttr()
  {
    $this->ref_id = null;
    $this->amount = null;
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.user.wallet');
  }
}
