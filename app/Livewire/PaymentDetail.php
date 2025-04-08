<?php

namespace App\Livewire;

use Livewire\Component;

class PaymentDetail extends Component
{
  public $payment;
  public function mount($id)
  {
    $this->payment = \App\Models\Payment::findOrFail($id);
  }
  public function render()
  {
    if ($this->payment->transaction_type == 'basic') {
      $list = $this->payment->transactions;
    } else {
      $list = $this->payment->singleTransaction()->get();
    }
    return view('livewire.payment-detail', compact('list'));
  }
}
