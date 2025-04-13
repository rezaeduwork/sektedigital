<?php

namespace App\Livewire\Components\Chat;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;

class Input extends Component
{
  #[Validate('required|regex:/\S/')]
  public $text;
  public $user;
  public $session;
  public $isStore;
  #[Url]
  public $tx_id;
  public $tx;
  public function mount($user, $session = null, $isStore)
  {
    $this->user = $user;
    $this->session = $session;
    if (isset($this->tx_id) && $this->tx_id !== null) {
      $this->tx = auth()->user()->transactions()->whereId($this->tx_id)->first();
      if (!$this->tx) {
        $this->tx = $this->user->transactions()->whereId($this->tx_id)->first();
      }
    }
  }
  public function send()
  {
    $this->validate();
    if (!$this->session) {
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
    $replyId = null;
    $replyType = null;
    if ($this->tx) {
      $replyId = $this->tx->id;
      $replyType = 'transaction';
    }
    $this->session->chats()->create([
      'text' => $this->text,
      'sender_id' => auth()->id(),
      'receiver_id' => $this->user->id,
      'reply_id' => $replyId,
      'reply_type' => $replyType,
      'type' => 'text'
    ]);
    $this->text = '';

    $this->dispatch('set_session', $this->session->id)->to(\App\Livewire\Chat::class);
    $this->dispatch('reload.' . $this->user->id, $this->session->id)->to(\App\Livewire\Components\Chat\MessageBody::class);
    if ($this->session && url('chat/' . $this->user->id) !== strtok(request()->headers->get('referer'), '?')) {
      $this->redirect(url('chat/' . $this->user->id), navigate: true);
    }
  }
  public function render()
  {
    return view('livewire.components.chat.input');
  }
}
