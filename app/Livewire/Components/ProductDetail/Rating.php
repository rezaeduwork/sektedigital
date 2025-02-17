<?php

namespace App\Livewire\Components\ProductDetail;

use Livewire\Component;
use Livewire\WithPagination;

class Rating extends Component
{
  use WithPagination;
  public $product;
  public $orderBy = 'best';
  public function mount($product)
  {
    $this->product = $product;
  }
  public function render()
  {
    $productRatingsList = $this->product->ratings();
    if ($this->orderBy == 'best') {
      $productRatingsList->orderBy('rating', 'desc');
    } else if ($this->orderBy == 'worst') {
      $productRatingsList->orderBy('rating', 'asc');
    } else {
      $productRatingsList->latest();
    }
    $productRatingsList = $productRatingsList->paginate(10);
    $total_rating = $this->product->ratings()->count();
    $total_rating_star = $total_rating > 0 ? $this->product->ratings()->avg('rating') : 0;
    return view('livewire.components.product-detail.rating', compact('total_rating', 'total_rating_star', 'productRatingsList'));
  }
}
