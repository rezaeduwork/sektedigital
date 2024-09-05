<?php

namespace App\Livewire;

use Livewire\Component;

class Checkout extends Component
{
  public function boot() {
    if (!session()->has('selectedCarts')) {
      $this->redirect('cart', navigate: true);
      return false;
    }
  }
  public function pay() {
    if (!session()->has('selectedCarts')) {
      $this->redirect('cart', navigate: true);
      return false;
    }
    $this->dispatch('alert-success', message: 'Pembayaran sedang di proses');
    \DB::beginTransaction();
    try {
      $tx = \App\Models\Transaction::create([
        'status' => 'unprocessed',
        'amount' => totalTransaction(),
        'customer_name' => auth()->user()->name,
        'customer_email' => auth()->user()->email,
        'customer_phone' => auth()->user()->phone,
        'user_id' => auth()->id()
      ]);
      $carts = auth()->user()->carts()->whereIn('id', session('selectedCarts'))->get();
      foreach ($carts as $row) {
        $tx->details()->create([
          'product_id' => $row->product_id,
          'price' => $row->product->price,
          'quantity' => $row->quantity,
          'subtotal' => $row->product->price * $row->quantity,
          'note' => $row->note
        ]);
      }
      transactionActivity($tx,auth()->id(),'unprocessed',(auth()->user()->name.' created transaction'));
      $tx->status = 'confirmed';
      $tx->save();
      transactionActivity($tx,auth()->id(),'confirmed',('confirmed by system'));
      \DB::commit();
      session()->forget('selectedCarts');
      $this->dispatch('alert-success', message: 'Transaksi berhasil dibuat.');
      $this->redirect('user/transaction', navigate: true);
    } catch (\Throwable $th) {
      \DB::rollBack();
      $this->dispatch('alert-error', message: 'Transaksi gagal.');
      dd($th);
    }
  }
  public function render()
  {
    $availableCarts = \App\Models\Cart::whereIn('id', session('selectedCarts'))->get();
    return view('livewire.checkout', compact('availableCarts'));
  }
}
