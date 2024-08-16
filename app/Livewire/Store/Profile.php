<?php

namespace App\Livewire\Store;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class Profile extends Component
{
  use WithFileUploads;
  #[Validate('nullable|image|max:1024', onUpdate: false)]
  public $photo;
  #[Validate('required|max:64', onUpdate: false)]
  public $name;
  #[Validate('required|max:64', onUpdate: false)]
  public $description;
  public function mount() {
    $this->name = auth()->user()->store->name;
  }
  public function update() {
    $this->validate();
    $store = auth()->user()->store;
    $store->name = $this->name;
    $store->description = $this->description;
    if ($this->photo) {
      $path = $this->photo->store(path: 'public');
      $store->photo = str_replace('public/','',$path);
    }
    $store->save();
    $this->dispatch('alert-success', message: 'Berhasil update profil kios!');
    $this->dispatch('reload')->self();
  }
  public function render()
  {
    return view('livewire.store.profile')->layout('components.layouts.app-dashboard');
  }
}
