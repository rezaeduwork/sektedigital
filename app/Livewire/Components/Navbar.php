<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class Navbar extends Component
{
  public function openChat()
  {
    $this->dispatch('open-widget')->to(ChatWidget::class);
  }
  public function polls()
  {
    $this->dispatch('reload')->self();
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.components.navbar');
  }
}
