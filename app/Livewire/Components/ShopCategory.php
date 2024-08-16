<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ShopCategory extends Component
{
  public $category;
  public $show = 5;
  public function mount($category = null) {
    $this->category = $category;
  }
  public function render()
  {
    return view('livewire.components.shop-category');
  }
}
