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
    return view('livewire.payment-detail');
  }
}
