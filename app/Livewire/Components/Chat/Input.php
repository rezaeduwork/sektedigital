<?php

namespace App\Livewire\Components\Chat;

use Livewire\Component;
use Livewire\Attributes\Validate;

class Input extends Component
{
  #[Validate('required|regex:/\S/')]
  public $text;
  public $user;
  public $session;
  public $isStore;
  public function mount($user, $session = null, $isStore)
  {
    $this->user = $user;
    $this->session = $session;
  }
  public function send()
  {
    $this->validate();
    if ($this->session) {
    } else {
      if ($this->isStore) {
        $data = [
          'user_id' => auth()->id(),
          'user_store_id' => $this->user->id,
          'store_id' => $this->user->store->id
        ];
      } else {
        $data = [
          'user_id' => $this->user->id,
          'user_store_id' => auth()->id(),
          'store_id' => auth()->user()->store->id
        ];
      }
      $this->session = \App\Models\ChatSession::create($data);
    }
    $this->session->chats()->create([
      'text' => $this->text,
      'sender_id' => auth()->id(),
      'receiver_id' => $this->user->id,
      'reply_id' => null,
      'type' => 'text'
    ]);
    $this->text = '';

    $this->dispatch('set_session', $this->session->id)->to(\App\Livewire\Chat::class);
    $this->dispatch('reload.' . $this->user->id, $this->session->id)->to(\App\Livewire\Components\Chat\MessageBody::class);
  }
  public function render()
  {
    return view('livewire.components.chat.input');
  }
}
