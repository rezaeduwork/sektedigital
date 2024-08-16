<?php

namespace App\Livewire\Store;

use Livewire\Component;

class Setting extends Component
{
  public function render()
  {
    return view('livewire.store.setting')->layout('components.layouts.app-dashboard');
  }
}
