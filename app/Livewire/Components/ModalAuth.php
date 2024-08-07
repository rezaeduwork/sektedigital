<?php

namespace App\Livewire\Components;
use Livewire\Attributes\On;

use Livewire\Component;

class ModalAuth extends Component
{
  public $form = 'login';
  #[On('toggle-form')]
  public function toggleForm() {
    $this->form = $this->form == 'login' ? 'register': 'login';
  }
  public function render()
  {
    return view('livewire.components.modal-auth');
  }
}
