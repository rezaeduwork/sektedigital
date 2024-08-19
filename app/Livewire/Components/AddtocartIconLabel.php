<?php

namespace App\Livewire\Components;

use Livewire\Component;

class AddtocartIconLabel extends Component
{
  public $product;
  public function mount($product) {
    $this->product = $product;
  }
  public function add($quantity = 1) {
    $product = \App\Models\Product::whereId($this->product->id)->where('status', 'active')->where('stock', '>', 0)->first();
    if (!$product) {
      $this->dispatch('alert-error', message: 'Produk tidak bisa dimasukkan keranjang, Silahkan reload halaman!');
      return false;
    }
    $existingCart = auth()->user()->carts()->where('product_id', $this->product->id)->first();
    if ($existingCart) {
      $this->dispatch('alert-warning', message: 'Produk sudah dimasukkan keranjang!');
      return false;
    }

    $existingCart = auth()->user()->carts()->create([
      'product_id' => $this->product->id,
      'quantity' => $quantity
    ]);
    $this->dispatch('alert-success', message: 'Produk berhasil dimasukkan keranjang!');
  }
  public function render()
  {
    return view('livewire.components.addtocart-icon-label');
  }
}
