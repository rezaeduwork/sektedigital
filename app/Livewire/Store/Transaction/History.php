<?php

namespace App\Livewire\Store\Transaction;

use Livewire\Component;
use Livewire\Attributes\On;

class History extends Component
{
  public $page;
  public function mount()
  {
    $this->page = 'Perlu Proses';
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.store.transaction.history')->layout('components.layouts.app-dashboard');
  }
}
