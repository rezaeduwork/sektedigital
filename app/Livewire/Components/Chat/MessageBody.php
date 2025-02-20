<?php

namespace App\Livewire\Components\Chat;

use Livewire\Component;
use Livewire\Attributes\On;

class MessageBody extends Component
{
  public $user;
  public $session;
  public function mount($user, $session)
  {
    $this->user = $user;
    $this->session = $session;
  }
  #[On('reload.{user.id}')]
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\Chat::where('chat_session_id', $this->session->id ?? null);
    $list = $list->get();
    return view('livewire.components.chat.message-body', compact('list'));
  }
}
