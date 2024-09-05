<?php

namespace App\Livewire\Components;

use Livewire\Component;

class SectionService extends Component
{
  public $activeTab = 'Pulsa';
  public function render()
  {
    return view('livewire.components.section-service');
  }
}
