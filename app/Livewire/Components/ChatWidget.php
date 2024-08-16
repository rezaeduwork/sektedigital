<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class ChatWidget extends Component
{
  public $open = false;
  #[On('open-widget')]
  public function toggleVisibility() {
    $this->open = !$this->open;
  }
  public function render()
  {
    return view('livewire.components.chat-widget');
  }
}
