<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Shop extends Component
{
  use WithPagination;

  public $category = null;
  public $perPage = 9;
  public $hasMorePages = true;
  public $orderBy = 'newest';

  public function loadMore()
  {
    if ($this->hasMorePages) {
      $this->perPage += 9;
    }
  }

  public function mount($path = null)
  {
    if ($path) {
      $paths = explode('/', $path);
      if (isset($paths[0])) {
        $this->category = \App\Models\CategoryProduct::findOrFail($paths[0]);
      }
    }
  }
  public function render()
  {
    $list = \App\Models\Product::query()->available();
    if ($this->category) {
      $list = $list->where('category_product_id', $this->category->id);
    }
    if ($this->orderBy == 'newest') {
      $list->latest();
    } else if ($this->orderBy == 'lowest_price') {
      $list->orderBy('price');
    } else if ($this->orderBy == 'popular') {
      $list->leftJoin('transaction_details', 'products.id', '=', 'transaction_details.product_id')->leftJoin('transactions', function ($join) {
        $join->on('transaction_details.transaction_id', '=', 'transactions.id')
          ->whereNotIn('transactions.status', ['unprocessed', 'cancelled', 'rejected', 'inspection']);
      })
        ->select('products.*')
        ->groupBy('products.id')
        ->orderByRaw('COUNT(transactions.id) DESC');
    } else if ($this->orderBy == 'best') {
      $list->leftJoin('product_ratings', 'products.id', '=', 'product_ratings.product_id')
        ->select('products.*')
        ->groupBy('products.id')
        ->orderByRaw('COUNT(product_ratings.id) * 2 DESC');
    }
    $list = $list->paginate($this->perPage);
    $this->hasMorePages = $list->hasMorePages();
    return view('livewire.shop', compact('list'));
  }
}
