<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Shop extends Component
{
  public $category = null;
  public function mount($path = null) {
    if ($path) {
      $paths = explode('/', $path);
      if (isset($paths[0])) {
        $this->category = \App\Models\CategoryProduct::find($paths[0]);
      }
    }
  }
  public function render()
  {
    return view('livewire.shop');
  }
}
