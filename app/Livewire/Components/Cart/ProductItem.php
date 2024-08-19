<?php

namespace App\Livewire\Components\Cart;

use Livewire\Component;
use Livewire\Attributes\On;

class ProductItem extends Component
{
  public $isSelected;
  public $product;
  public $cart;
  public $note;
  public function select($cartId) {
    $this->dispatch('cart-select', cartId: $cartId)->to(\App\Livewire\Cart::class);
  }
  public function updatingNote($value) {
    if (strlen($this->note) > 100) {
      $this->dispatch('alert-error', message: "Note maksimal 100 huruf!");
      throw new Exception;
    }
  }
  public function updatedNote($value) {
    $this->cart->note = $value;
    $this->cart->save();
    $this->dispatch('alert-success', message: 'Note ditambah.');
  }
  public function changeQuantity($cartId,$type) {
    if ($type == 'plus') {
      $this->dispatch('change-quantity', cartId: $cartId, quantity: $this->cart->quantity+1);
      return true;
    } elseif($type == 'minus') {
      $this->dispatch('change-quantity', cartId: $cartId, quantity: $this->cart->quantity-1);
      return true;
    }
    if ((int)$type > 0) {
      $this->dispatch('change-quantity', cartId: $cartId, quantity: $type);
      return true;
    }
    $this->dispatch('alert-error', message: 'Jumlah minimal 1');
  }
  public function mount($cart, $product, $isSelected) {
    $this->cart = $cart;
    $this->product = $product;
    $this->isSelected = $isSelected;
    $this->note = $this->cart->note;
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.components.cart.product-item');
  }
}
