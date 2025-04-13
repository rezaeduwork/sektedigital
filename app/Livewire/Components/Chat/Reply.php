<?php

namespace App\Livewire\Components\Chat;

use Livewire\Component;

class Reply extends Component
{
  public $chat;
  public function mount($chat)
  {
    $this->chat = $chat;
  }
  public function render()
  {
    return view('livewire.components.chat.reply');
  }
}
