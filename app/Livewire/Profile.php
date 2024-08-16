<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Profile extends Component
{
  public $page = 'Profil';

  public function mount() {
    if (session()->has('page')) {
      $this->page = session('page');
      $this->dispatch('alert-error', message: 'Silahkan buat kios terlebih dahulu.');
    }
  }

  #[On('change-page')]
  public function changePage($page) {
    $this->page = $page;
  }

  public function render()
  {
    return view('livewire.profile');
  }
}
