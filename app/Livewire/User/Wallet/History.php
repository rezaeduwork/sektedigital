<?php

namespace App\Livewire\User\Wallet;

use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
  use WithPagination;
  public $activeTab = 'all';
  public $search = '';
  public function updatedSearch() {
    $this->resetPage();
  }
  public function updatedActiveTab() {
    $this->resetPage();
  }
  public function render()
  {
    $list = \App\Models\UserBalance::query();
    if ($this->activeTab != 'all' && $this->activeTab) {
      $list->whereStatus($this->activeTab);
    }
    if ($this->search) {
      $search = $this->search;
      $list->where(function($query) use ($search) {
        $query->where('name', 'like', "%$search%")->orWhere('description', 'like', "%$search%");
      });
    }
    $list = $list->latest()->paginate(2);
    return view('livewire.user.wallet.history', compact('list'));
  }
}
