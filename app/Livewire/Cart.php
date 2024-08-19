<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Cart extends Component
{
  public $totalCart;
  public $selected = [];
  public $selectedAll;
  public function updatedSelectedAll($value) {
    $this->selectAll();
  }
  public function selectAll()
  {
    $cartIds = auth()->user()->carts()->availableProduct()->get();
    if (sizeof($this->selected) < $this->totalCart) {
      $this->selected = $cartIds->pluck('id')->toArray();
    } else {
      $this->selected = [];
    }
  }
  public function checkout() {
    if (sizeof($this->selected) <= 0) {
      $this->dispatch('alert-error', message: 'Silahkan pilih produk di keranjang!');
      return false;
    }
    session()->put('selectedCarts', $this->selected);
    $this->redirect('checkout', navigate: true);
  }
  #[On('delete-cart')]
  public function deleteCart($cartId)
  {
    auth()->user()->carts()->whereId($cartId)->delete();
    $this->dispatch('alert-success', message: 'Cart diapus');
  }
  #[On('change-quantity')]
  public function changeQuantity($cartId,$quantity)
  {
    $cart = auth()->user()->carts()->whereId($cartId)->first();
    if (!$cart) {
      $this->dispatch('alert-error', message: 'Cart tidak ada, silahkan refresh halaman');
      $this->dispatch('render')->self();
      return false;
    }
    if ($cart->product->stock < $quantity) {
      $this->dispatch('alert-error', message: 'Stok melebihi batas. tersisa '.$cart->product->stock);
      $this->dispatch('render')->self();
      return false;
    }

    $cart->update([
      'quantity' => $quantity
    ]);
    $this->dispatch('alert-success', message: 'Kuantitas berhasil diubah!');
    $this->dispatch('render')->self();
  }
  #[On('cart-select')]
  public function select($cartId)
  {
    if (in_array($cartId, $this->selected)) {
      $this->selected = array_filter($this->selected, function ($id) use ($cartId) {
        return $id !== $cartId;
      });
    } else {
      $this->selected[] = $cartId;
    }
    if(sizeof($this->selected) == $this->totalCart) {
      $this->selectedAll = true;
    } else {
      $this->selectedAll = false;
    }
  }
  public function mount()
  {
    $this->totalCart = auth()->user()->carts()->availableProduct()->count();
    if (session()->has('selectedCarts')) {
      session()->forget('selectedCarts');
    }
  }
  #[On('render')]
  public function render()
  {
    return view('livewire.cart');
  }
}
