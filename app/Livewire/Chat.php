<?php

namespace App\Livewire;

use Livewire\Component;

class Chat extends Component
{
  public $user;
  public $session;
  public function mount($user_id = null)
  {
    if ($user_id) {
      $this->user = \App\Models\User::findOrFail($user_id);
    }
    if ($this->user) {
      $this->session = \App\Models\ChatSession::where(function ($query) {
        $query->where('user_id', auth()->id())->orWhere('user_store_id', auth()->id());
      })->firstOrFail();
    }
  }
  public function render()
  {
    return view('livewire.chat');
  }
}
