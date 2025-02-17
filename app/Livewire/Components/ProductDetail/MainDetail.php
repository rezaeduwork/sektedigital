<?php

namespace App\Livewire\Components\ProductDetail;

use Livewire\Component;
use Livewire\Attributes\On;

class MainDetail extends Component
{
  public $product;
  public function mount($product)
  {
    $this->product = $product;
  }
  public function autoCart()
  {
    $product = \App\Models\Product::whereId($this->product->id)->where('status', 'active')->where('stock', '>', 0)->first();
    if (!$product) {
      $this->dispatch('alert-error', message: 'Produk tidak bisa dimasukkan keranjang, Silahkan reload halaman!');
      return false;
    }
    $existingCart = auth()->user()->carts()->where('product_id', $this->product->id)->first();
    if (!$existingCart) {
      $existingCart = auth()->user()->carts()->create([
        'product_id' => $this->product->id,
        'quantity' => 1
      ]);
    }

    session()->put('selectedCarts', [$existingCart->id]);
    return $this->redirect('checkout', navigate: true);
  }
  #[On('reload')]
  public function render()
  {
    $inCart = auth()->check() && auth()->user()->carts()->where('product_id', $this->product->id)->first();
    return view('livewire.components.product-detail.main-detail', compact('inCart'));
  }
}
