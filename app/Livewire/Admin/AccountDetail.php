<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AccountDetail extends Component
{
  public $user;
  public $tab = 'information';
  public function mount($id) {
    $this->user = \App\Models\User::findOrFail($id);
  }
  public function render()
  {
    return view('livewire.admin.account-detail')->layout('components.layouts.app-admin');
  }
}
