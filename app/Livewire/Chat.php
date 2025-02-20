<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Chat extends Component
{
  public $user;
  public $session;
  public $isStore;
  public function mount($path = null)
  {
    if (str_contains($path, 'new/store/')) {
      $id = str_replace('new/store/', '', $path);
      if ($id == auth()->user()->store->id) {
        abort(404);
      }
      $store = \App\Models\Store::where('id', $id)->firstOrFail();
      $this->user = $store->user;
      $this->isStore = true;
      $this->reloadSession();
    } else if (str_contains($path, 'new/member/')) {
      $id = str_replace('new/member/', '', $path);
      if ($id == auth()->id()) {
        abort(404);
      }
      $member = \App\Models\User::where('id', $id)->firstOrFail();
      $this->user = $member;
      $this->isStore = false;
      $this->reloadSession();
    } else {
      if ($path) {
        $this->user = \App\Models\User::findOrFail($path);
      }
      if ($this->user) {
        $this->reloadSession();
        if (!$this->reloadSession()) {
          abort(404);
        }
      }
    }
  }
  public function reloadSession()
  {
    if (!$this->user) {
      return null;
    }
    $id = $this->user->id;
    $this->session = \App\Models\ChatSession::where(function ($query) use ($id) {
      $query->where(['user_id' => auth()->id(), 'user_store_id' => $id])->orWhere(['user_store_id' => auth()->id(), 'user_id' => $id]);
    })->first();
    return $this->session;
  }
  #[On('set_session')]
  public function setSession($id)
  {
    $this->session = \App\Models\ChatSession::find($id);
    $this->dispatch('reload')->self();
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.chat');
  }
}
