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
    $list = \App\Models\Product::where('category_product_id', $this->category->id)->where('status', 'active')->paginate($this->perPage);
    $this->hasMorePages = $list->hasMorePages();
    return view('livewire.shop', compact('list'));
  }
}
