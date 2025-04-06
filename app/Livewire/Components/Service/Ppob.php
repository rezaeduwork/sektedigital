<?php

namespace App\Livewire\Components\Service;

use Livewire\Component;

class Ppob extends Component
{
  public $activeTab = 'Game Online';
  public function render()
  {
    return view('livewire.components.service.ppob');
  }
}
