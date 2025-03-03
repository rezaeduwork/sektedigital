<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Transaction extends Component
{
  use WithPagination;
  public $search;
  public $searchStore;
  public $sort;
  public function updateStatus($id, $status)
  {
    \App\Models\Transaction::find($id)->update(['status' => $status]);
    $this->dispatch('reload')->self();
  }
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\Transaction::query()->has('user')->has('store');
    if ($this->search) {
      $list->where(function ($query) {
        $query->whereId('id', $this->search)->orWhere('user_id', $this->search);
      });
    }
    if ($this->searchStore) {
      $list->whereHas('store', function ($query) {
        $query->where('name', 'like', '%' . $this->searchStore . '%');
      });
    }
    if (!$this->sort) {
      $list->latest();
    }
    $list = $list->simplePaginate(10);
    return view('livewire.admin.transaction', compact('list'))->layout('components.layouts.app-admin');
  }
}
