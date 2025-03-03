<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Withdrawal extends Component
{
  public $search;
  public $status;
  public $sort;
  public function approve($id)
  {
    \App\Models\UserBalance::find($id)->update(['status' => 'success']);
    $this->dispatch('alert-success', message: "Berhasil approve withdrawal!");
    $this->dispatch('reload')->self();
  }
  public function render()
  {
    $list = \App\Models\UserBalance::query()->where('type', 'withdraw')->whereStatus('pending');
    if ($this->search) {
      $list->where(function ($query) {
        $query->where('user_id', $this->search);
      });
    }
    if ($this->status) {
      $list->where('status', $this->status);
    }
    if (!$this->sort) {
      $list->latest();
    }
    $list = $list->simplePaginate(10);
    return view('livewire.admin.withdrawal', compact('list'))->layout('components.layouts.app-admin');
  }
}
