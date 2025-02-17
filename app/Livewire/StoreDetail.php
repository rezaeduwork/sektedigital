<?php

namespace App\Livewire;

use Livewire\Component;

class StoreDetail extends Component
{
  // FILTERS
  public $search;
  public $filterCategory;

  public $store;
  public $categoryIds;
  public $perPage = 9;
  public $hasMorePages = true;

  public function mount($id)
  {
    $this->store = \App\Models\Store::findOrFail($id);
    $this->categoryIds = \App\Models\Product::select('category_product_id')->where('store_id', $this->store->id)->groupBy('category_product_id')->get()->pluck('category_product_id');
  }
  public function loadMore()
  {
    if ($this->hasMorePages) {
      $this->perPage += 9;
    }
  }
  public function render()
  {
    $list = \App\Models\Product::where('store_id', $this->store->id);
    if ($this->filterCategory) {
      $list = $list->where('category_product_id', $this->filterCategory);
    }
    $list = $list->where('status', 'active')->paginate($this->perPage);
    $this->hasMorePages = $list->hasMorePages();
    $categories = \App\Models\CategoryProduct::whereIn('id', $this->categoryIds)->get();
    return view('livewire.store-detail', compact('list', 'categories'));
  }
}
