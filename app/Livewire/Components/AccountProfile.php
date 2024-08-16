<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Validate;

class AccountProfile extends Component
{
  #[Validate('required|max:64', onUpdate: false)]
  public $name;
  #[Validate('required|numeric|digits_between:5,15', onUpdate: false)]
  public $phone;
  #[Validate('required|string', onUpdate: false)]
  public $gender;
  #[Validate('required|date', onUpdate: false)]
  public $birthdate;
  #[Validate('required|string', onUpdate: false)]
  public $address;
  public function mount() {
    $this->name = auth()->user()->name;
    $this->phone = auth()->user()->phone;
    $this->gender = auth()->user()->gender;
    $this->birthdate = auth()->user()->birthdate;
    $this->address = auth()->user()->address;
  }
  public function update() {
    $this->validate();
    if (\App\Models\User::where('id', '<>', auth()->id())->where('phone', $this->phone)->exists()) {
      $this->addError('phone', 'Nomor HP sudah digunakan!');
      return false;
    }
    $user = auth()->user();
    $user->name = $this->name;
    $user->phone = $this->phone;
    $user->gender = $this->gender;
    $user->birthdate = $this->birthdate;
    $user->address = $this->address;
    $user->save();

    $this->dispatch('alert-success', message: 'Profil di ubah!');
  }
  public function render()
  {
    return view('livewire.components.account-profile');
  }
}
