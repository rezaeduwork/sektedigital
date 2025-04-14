<?php

namespace App\Livewire\Store\Transaction;

use Livewire\Component;

class Review extends Component
{
  public function render()
  {
    return view('livewire.store.transaction.review')->layout('components.layouts.app-dashboard');
  }
}
