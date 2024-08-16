<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\Computed;
class SectionRecommendation extends Component
{
  public int $on_page = 8;
  public int $total = 40;
  #[Computed]
  public function recommendationProduct()
  {
    return \App\Models\Product::available()->latest()->take($this->on_page)->get();
  }
  public function loadMore(): void
  {
    $this->on_page += 8;
  }
  public function mount() {
    $this->total = \App\Models\Product::available()->latest()->count();
  }
  public function render()
  {
    return view('livewire.components.section-recommendation');
  }
}
