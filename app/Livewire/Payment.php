<?php

namespace App\Livewire;

use Livewire\Component;

class Payment extends Component
{
  public $payment;
  public $paymentDetail;
  public function mount($id)
  {
    $this->payment = \App\Models\Payment::findOrFail($id);
    $detail = checkPayment($this->payment);
    if ($detail['status']) {
      $this->paymentDetail = $detail;
    }
  }
  public function paymentPool()
  {
    if ($this->payment->status == 'settlement') {
      $this->redirect(url('payment/' . $this->payment->id . '/detail'));
    }
  }
  public function render()
  {
    return view('livewire.payment');
  }
}
