<?php

namespace App\Livewire\Store\Transaction;

use Livewire\Component;
use Livewire\Attributes\On;

class Rating extends Component
{
  public $page;
  public function mount() {}
  #[On('reload')]
  public function render()
  {
    return view('livewire.store.transaction.rating')->layout('components.layouts.app-dashboard');
  }
}
