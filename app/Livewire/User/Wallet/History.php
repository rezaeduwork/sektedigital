<?php

namespace App\Livewire\User\Wallet;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class History extends Component
{
  use WithPagination;
  public $activeTab = 'all';
  public $search = '';
  public function updatedSearch()
  {
    $this->resetPage();
  }
  public function updatedActiveTab()
  {
    $this->resetPage();
  }
  #[On('reload')]
  public function render()
  {
    $list = \App\Models\UserBalance::query();
    $list->where('user_id', auth()->id());
    if ($this->activeTab != 'all' && $this->activeTab) {
      $list->whereStatus($this->activeTab);
    }
    if ($this->search) {
      $search = $this->search;
      $list->where(function ($query) use ($search) {
        $query->where('name', 'like', "%$search%")->orWhere('description', 'like', "%$search%");
      });
    }
    $list = $list->latest()->paginate(10);
    return view('livewire.user.wallet.history', compact('list'));
  }
}
