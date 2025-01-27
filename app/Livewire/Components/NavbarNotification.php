<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class NavbarNotification extends Component
{
  public function read($id)
  {
    $notification = \App\Models\Notification::findOrFail($id);
    $notification->read_at = now();
    $notification->save();
    $this->dispatch('reload')->self();
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.components.navbar-notification');
  }
}
