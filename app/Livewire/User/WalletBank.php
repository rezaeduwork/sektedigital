<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

class WalletBank extends Component
{
  #[Validate('required|in:bank,ewallet')]
  public $type = 'bank';
  #[Validate('required')]
  public $name;
  #[Validate('required')]
  public $ref;
  public $id;
  public function setUpdateAttr($id, $type, $name, $ref)
  {
    $this->id = $id;
    $this->name = $name;
    $this->type = $type;
    $this->ref = $ref;
  }
  public function doUpdate()
  {
    if (auth()->user()->banks()->whereId($this->id)->first()) {
      $existingBank = auth()->user()->banks()->whereId($this->id)->first();
      if ($existingBank) {
        $existingBank->update([
          'type' => $this->type,
          'name' => $this->name,
          'ref' => $this->ref,
        ]);
        $this->dispatch('alert-success', message: "Berhasil!");
      }
    }
    $this->resetProp();
  }
  public function store()
  {
    $bank = auth()->user()->banks()->create([
      'type' => $this->type,
      'name' => $this->name,
      'ref' => $this->ref,
    ]);
    $this->resetProp();
    $this->dispatch('alert-success', message: "Berhasil!");
  }
  public function resetProp()
  {
    $this->id = null;
    $this->name = null;
    $this->type = null;
    $this->ref = null;
  }
  public function delete($id)
  {
    auth()->user()->banks()->whereId($id)->delete();
    $this->dispatch('alert-success', message: "Berhasil hapus!");
  }

  #[On('reload')]
  public function render()
  {
    return view('livewire.user.wallet-bank');
  }
}
