<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Validate;

class FormRegister extends Component
{
  #[Validate('required|min:3|max:64', onUpdate: false)]
  public $name;
  #[Validate('required|email', onUpdate: false)]
  public $email;
  #[Validate('required|min:8|max:64', onUpdate: false)]
  public $password;
  public function register() {
    $this->validate();
    $existingUser = \App\Models\User::whereEmail(request('email'))->first();
    if ($existingUser) {
      $this->addError('email', 'Email sudah terdaftar!!!');
      return false;
    }

    $user = \App\Models\User::create([
      'name' => $this->name,
      'email' => $this->email,
      'password' => $this->password,
      'role' => 'member'
    ]);
    if ($user) {
      auth()->login($user);
      $this->redirect('/');
      $this->dispatch('alert-success', message: 'Daftar Berhasil');
      return;
    }
    $this->dispatch('alert-error', message: 'Ada error tidak terduga!');
  }
  public function render()
  {
    return view('livewire.components.form-register');
  }
}
