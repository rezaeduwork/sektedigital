<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Withdrawal extends Component
{
  use WithPagination;
  public $search;
  public $status;
  public $type;
  public $sort;
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\UserBalance::query()->has('user');
    if ($this->search) {
      $list->where(function ($query) {
        $query->where('user_id', $this->search);
      });
    }
    if ($this->status) {
      $list->where('status', $this->status);
    }
    if ($this->type) {
      $list->where('type', $this->type);
    }
    if (!$this->sort) {
      $list->latest();
    }
    $list = $list->simplePaginate(10);
    return view('livewire.admin.withdrawal', compact('list'))->layout('components.layouts.app-admin');
  }
}
