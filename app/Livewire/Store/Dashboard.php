<?php

namespace App\Livewire\Store;

use Livewire\Component;

class Dashboard extends Component
{
  public function render()
  {
    return view('livewire.store.dashboard')->layout('components.layouts.app-dashboard');
  }
}
