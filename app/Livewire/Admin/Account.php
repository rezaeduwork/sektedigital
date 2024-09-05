<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Account extends Component
{
  public function render()
  {
    $list = \App\Models\User::query();

    $list = $list->paginate(10);
    return view('livewire.admin.account',compact('list'))->layout('components.layouts.app-admin');
  }
}
