<?php

namespace App\Livewire\Admin\ProductInstant;

use Livewire\Component;

class ReloadCrypto extends Component
{
  public function reloadData()
  {
    \App\Models\ProductInstant::reloadCrypto();
    $this->dispatch('reload')->to(\App\Livewire\Admin\ProductInstant::class);
    $this->dispatch('alert-success', message: "Reload berhasil!");
  }
  public function render()
  {
    return view('livewire.admin.product-instant.reload-crypto');
  }
}
