<?php

namespace App\Livewire\Components\Chat;

use Livewire\Component;
use Livewire\Attributes\On;

class Contact extends Component
{
  public $tab;
  // public function openChat($userId, $) {
  //   $this->dispatch('set_session', $this->session->id)->to(\App\Livewire\Chat::class);
  //   $this->dispatch('reload.' . $this->user->id, $this->session->id)->to(\App\Livewire\Components\Chat\MessageBody::class);
  // }
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\ChatSession::where(function ($query) {
      $query->where('user_id', auth()->id())->orWhere('user_store_id', auth()->id());
    });
    $list = $list->get();
    return view('livewire.components.chat.contact', compact('list'));
  }
}
