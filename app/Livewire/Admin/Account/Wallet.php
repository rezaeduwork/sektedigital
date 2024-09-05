<?php

namespace App\Livewire\Admin\Account;

use Livewire\Component;

class Wallet extends Component
{
  public $user;
  public $name;
  public $type;
  public $status = 'success';
  public $amount;
  public $description;
  public function mount($user) {
    $this->user = $user;
  }
  public function store() {
    \App\Models\UserBalance::create([
      'user' => $this->user,
      'name' => $this->name,
      'type' => $this->type,
      'status' => $this->status,
      'amount' => $this->amount,
      'description' => $this->description,
      'uid' => $this->user->id.uniqid().time()
    ]);
    $this->dispatch('alert-success',message: 'Berhasil');
    $this->dispatch('$refresh');
  }
  public function delete($id) {
    \App\Models\UserBalance::find($id)->delete();
    $this->dispatch('alert-success',message: 'Berhasil');
    $this->dispatch('$refresh');
  }
  public function render()
  {
    $list = \App\Models\UserBalance::query();

    $list = $list->latest()->get();
    return view('livewire.admin.account.wallet', compact('list'));
  }
}
