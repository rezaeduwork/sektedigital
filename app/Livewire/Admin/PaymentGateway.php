<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\PaymentGateway as PaymentGatewayModel;

class PaymentGateway extends Component
{
  use WithPagination;

  public $search;

  #[On('reload')]
  public function render()
  {
    $list = PaymentGatewayModel::query();

    if ($this->search) {
      $list->where('name', 'like', '%' . $this->search . '%')
        ->orWhere('display_name', 'like', '%' . $this->search . '%');
    }

    $list = $list->paginate(10);

    return view('livewire.admin.payment-gateway', compact('list'))
      ->layout('components.layouts.app-admin');
  }
}
