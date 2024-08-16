<?php

namespace App\Livewire\Errors;

use Livewire\Component;

class Unauthenticated extends Component
{
  public function mount() {
    session()->flash('unauthenticated', true);
    $this->redirect('/',navigate: true);
  }
  public function render()
  {
    $layout = 'components.layouts.app';
    $prefix = dirname(str_replace(url('/'), '', session('prev_url')));
    if ($prefix == '/store') {
      $layout = 'components.layouts.app-dashboard';
    }
    return view('livewire.errors.unauthenticated')->layout($layout);
  }
}
