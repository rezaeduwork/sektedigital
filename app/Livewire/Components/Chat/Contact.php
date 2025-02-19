<?php

namespace App\Livewire\Components\Chat;

use Livewire\Component;

class Contact extends Component
{
  public $tab;

  public function render()
  {
    $list = \App\Models\ChatSession::where(function ($query) {
      $query->where('user_id', auth()->id())->orWhere('user_store_id', auth()->id());
    });
    $list = $list->get();
    return view('livewire.components.chat.contact', compact('list'));
  }
}
