<?php

namespace App\Livewire\Components;

use Livewire\Component;

class MenuUser extends Component
{
  public function logout() {
    auth()->logout();
    $this->redirect('/');
  }
  public function render()
  {
    return view('livewire.components.menu-user');
  }
}
