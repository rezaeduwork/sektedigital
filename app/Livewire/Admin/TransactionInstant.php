<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class TransactionInstant extends Component
{
  use WithPagination;
  public $search;
  public $sort;
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\TransactionSingle::query();
    if ($this->search) {
      $list->where(function ($query) {
        $query->where('id', str_replace('#', '', $this->search))->orWhereHas('product', function ($query) {
          $query->where('title', 'like', '%' . $this->search . '%');
        });
      });
    }
    if (!$this->sort) {
      $list->latest();
    }
    $list = $list->simplePaginate(10);
    return view('livewire.admin.transaction-instant', compact('list'))->layout('components.layouts.app-admin');
  }
}
