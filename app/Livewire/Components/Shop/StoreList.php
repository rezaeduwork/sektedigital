<?php

namespace App\Livewire\Components\Shop;

use Livewire\Component;

class StoreList extends Component
{
  public $search;
  public $category;
  public function mount($search = null, $category = null)
  {
    $this->search = $search;
    $this->category = $category;
  }
  public function render()
  {
    $list = \App\Models\Store::withCount('products')->whereStatus('verified')->has('user')->has('products');
    if (auth()->check()) {
      $list->where('user_id', '<>', auth()->id());
    }
    if ($this->search) {
      $list->where('name', 'like', '%' . $this->search . '%');
    }
    if ($this->category) {
      $categoryId = $this->category->id;
      $list->whereHas('products', function ($query) use ($categoryId) {
        $query->where('category_product_id', $categoryId);
      });
    }
    $list = $list->orderBy('products_count', 'desc')->take(10)->get();
    return view('livewire.components.shop.store-list', compact('list'));
  }
}
