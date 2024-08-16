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
  public function login() {
    $this->validate();
    $credentials = [
      'email' => $this->email,
      'password' => $this->password
    ];
    if (auth()->attempt($credentials)) {
      $this->redirect('/');
      $this->dispatch('alert-success', message: 'Login Berhasil');
      return;
    }
    $this->dispatch('alert-error', message: 'Username / Password Salah!');
  }
  public function render()
  {
    return view('livewire.components.form-login');
  }
}
