<?php

namespace App\Livewire\Store;

use Livewire\Component;

class WhatsappBotDoc extends Component
{
  public $store;

  public function mount()
  {
    $this->store = auth()->user()->store;
  }

  public function render()
  {
    return view('livewire.store.whatsapp-bot-doc')
      ->layout('components.layouts.app-dashboard');
  }
}
