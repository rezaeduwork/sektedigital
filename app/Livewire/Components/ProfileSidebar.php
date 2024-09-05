<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class ProfileSidebar extends Component
{
  use WithFileUploads;
  public $page;
  #[Validate('image|max:1024')]
  public $photo;
  public function updatedPhoto() {
    $path = $this->photo->store(path: 'public');
    $user = auth()->user();
    $user->photo = str_replace('public/', '', $path);
    $user->save();
    $this->dispatch('alert-success', message: 'Foto berhasil disimpan!');
  }
  public function render()
  {
    return view('livewire.components.profile-sidebar');
  }
}
