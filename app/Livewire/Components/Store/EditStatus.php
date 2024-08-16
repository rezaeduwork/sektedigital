<?php

namespace App\Livewire\Components\Store;

use Livewire\Component;
use Livewire\Attributes\Validate;

class EditStatus extends Component
{
  public $product;
  #[Validate('required|in:inactive,active', onUpdate: false)]
  public $status;
  public function updatedStatus() {
    if ($this->status) {
      $this->product->status = $this->status;
      $this->product->save();
      $this->dispatch('alert-success', message: 'Status diubah');
    } else {
      $this->status = $this->product->status;
    }
  }
  public function mount($product) {
    $this->product = $product;
    $this->status = $this->product->status;
  }
  public function render()
  {
    return view('livewire.components.store.edit-status');
  }
}
