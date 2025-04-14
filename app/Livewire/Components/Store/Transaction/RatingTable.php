<?php

namespace App\Livewire\Components\Store\Transaction;

use Livewire\Component;
use Livewire\WithPagination;

class RatingTable extends Component
{
  use WithPagination;
  public function render()
  {
    $list = \App\Models\ProductRating::whereHas('store', function ($query) {
      $query->whereStore_id(auth()->user()->store->id);
    })->paginate(5);
    return view('livewire.components.store.transaction.rating-table', compact('list'));
  }
}
