<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;

class Transaction extends Component
{
  public $activeTab = null;
  public function mount()
  {
    if (request('tab')) {
      $this->activeTab = request('tab');
    }
  }
  public function pay($paymentId)
  {
    $payment = auth()->user()->payments()->findOrFail($paymentId);
    $payment->status = 'settlement';
    $payment->save();
    $payment->transactions()->update([
      'status' => 'confirmed'
    ]);

    // $this->dispatch('do-payment', url: );
    $this->dispatch('alert-success', message: 'Pembayaran Berhasil.');
    $this->dispatch('reload')->self();
  }
  #[On('reload')]
  public function render()
  {
    $transactions = queryListUserTransaction($this->activeTab);

    $transactions = $transactions->latest()->get();
    return view('livewire.user.transaction', compact('transactions'));
  }
}
