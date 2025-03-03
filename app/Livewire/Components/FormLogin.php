<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Validate;

class FormLogin extends Component
{
  #[Validate('required|email', onUpdate: false)]
  public $email;

  #[Validate('required|min:8', onUpdate: false)]
  public $password;
  public function login()
  {
    $this->validate();
    $credentials = [
      'email' => $this->email,
      'password' => $this->password
    ];
    if (auth()->attempt($credentials)) {
      if (auth()->user()->role == 'admin') {
        $this->dispatch('alert-success', message: 'Selamat Datang');
        $this->redirect('/admin');
        return;
      }
      auth()->user()->reloadBalance();
      $this->dispatch('alert-success', message: 'Selamat Datang');
      $this->redirect('/');
      return;
    }
    $this->dispatch('alert-error', message: 'Username / Password Salah!');
  }
  public function render()
  {
    return view('livewire.components.form-login');
  }
}
