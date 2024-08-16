<?php

namespace App\Livewire\Components;

use Livewire\Component;

class PopularCard extends Component
{
  public $product;
  public function mount($product) {
    $this->product = $product;
  }
  public function render()
  {
    return view('livewire.components.popular-card');
  }
}
