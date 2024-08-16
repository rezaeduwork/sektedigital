<?php

namespace App\Livewire\Store\Transaction;

use Livewire\Component;

class History extends Component
{
  public $page;
  public function mount() {
    $this->page = 'Perlu Proses';
  }
  public function render()
  {
    return view('livewire.store.transaction.history')->layout('components.layouts.app-dashboard');
  }
}
