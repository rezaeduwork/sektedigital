<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class AccountStore extends Component
{
  use WithFileUploads;
  #[Validate('required|max:64', onUpdate: false)]
  public $name;
  #[Validate('required|image|max:1024', onUpdate: false)]
  public $photo;
  public function store() {
    $this->validate();
    $store = auth()->user()->store;
    if (!$store) {
      $path = $this->photo->store(path: 'public');
      $store = auth()->user()->store()->create([
        'name' => $this->name,
        'photo' => str_replace('public/','',$path),
        'status' => 'verified'
      ]);
      $this->dispatch('alert-success', message: 'Berhasil membuat kios!');
      $this->dispatch('reload')->self();
      $this->redirect('store');
    }
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.components.account-store');
  }
}
