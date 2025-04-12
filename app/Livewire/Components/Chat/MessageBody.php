<?php

namespace App\Livewire\Components\Chat;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class MessageBody extends Component
{
  public $user;
  public $session;
  #[Url]
  public ?string $tx_id;
  public $tx;
  public function mount($user, $session)
  {
    $this->user = $user;
    $this->session = $session;
    if (isset($this->tx_id) && $this->tx_id !== null) {
      $this->tx = auth()->user()->transactions()->whereId($this->tx_id)->first();
    }
  }
  #[On('reload.{user.id}')]
  #[On('reload')]
  public function render()
  {
    \App\Models\Chat::whereNull('read_at')->where('receiver_id', auth()->id())->update(['read_at' => now()]);
    $list = \App\Models\Chat::where('chat_session_id', $this->session->id ?? null);
    $list = $list->get();
    return view('livewire.components.chat.message-body', compact('list'));
  }
}
