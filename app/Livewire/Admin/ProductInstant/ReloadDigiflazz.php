<?php

namespace App\Livewire\Admin\ProductInstant;

use Livewire\Component;

class ReloadDigiflazz extends Component
{
  public function reloadData()
  {
    $response = \App\Models\ProductInstant::reloadDigiflazz();
    if (!$response['success']) {
      $this->dispatch('alert-error', message: "Gagal! " . $response['message']);
      return;
    }
    $this->dispatch('reload')->to(\App\Livewire\Admin\ProductInstant::class);
    $this->dispatch('alert-success', message: "Reload digiflazz berhasil!");
  }
  public function render()
  {
    return view('livewire.admin.product-instant.reload-digiflazz');
  }
}
