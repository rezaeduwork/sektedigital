<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class NavbarNotification extends Component
{
  public int $on_page = 3;
  public function read($id)
  {
    $notification = \App\Models\Notification::findOrFail($id);
    $notification->read_at = now();
    $notification->save();
    $this->dispatch('reload')->self();
  }
  public function loadMore(): void
  {
    $this->on_page += 8;
  }
  public function readAll(): void
  {
    auth()->user()->unreadNotifications()->update([
      'read_at' => now()
    ]);
    $this->dispatch('reload')->to(\App\Livewire\Components\Navbar::class);
    $this->dispatch('reload')->self();
  }
  #[On('reload')]
  public function render()
  {
    $unreadNotifications = 0;
    if (auth()->check()) {
      $unreadNotifications = auth()->user()->unreadNotifications()->take($this->on_page)->get();
    }
    return view('livewire.components.navbar-notification', compact('unreadNotifications'));
  }
}
