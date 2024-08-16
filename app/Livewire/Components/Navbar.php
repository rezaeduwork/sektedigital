<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Navbar extends Component
{
  public function openChat() {
    $this->dispatch('open-widget')->to(ChatWidget::class);
  }
  public function render()
  {
    return view('livewire.components.navbar');
  }
}
