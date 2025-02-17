<?php

namespace App\Livewire;

use Livewire\Component;

class Checkout extends Component
{
  // PAYMENT ATTRIBUTE
  public $selectedPayment;
  public $taxFee = 0;
  public $platformFee = 0;
  public $productFee = 0;
  public $totalPayment = 0;

  public $channels = [];
  // FETCH
  public function boot()
  {
    if (!session()->has('selectedCarts')) {
      $this->redirect('cart', navigate: true);
      return false;
    }
  }
  public function mount()
  {
    $this->productFee = totalTransaction();
    $this->reloadTotalPayment();
    $this->getChannels();
  }
  public function getChannels()
  {
    $channelsData = tripay()->getPaymentChannels();
    if ($channelsData) {
      $this->channels = $channelsData['data'];
    }
  }
  // END FETCH

  // ACTIONS
  public function reloadTotalPayment()
  {
    $this->totalPayment = $this->taxFee + $this->platformFee + $this->productFee;
  }
  public function pay()
  {
    if (!session()->has('selectedCarts')) {
      $this->redirect('cart', navigate: true);
      return false;
    }
    $this->dispatch('alert-success', message: 'Pembayaran sedang di proses');
    \DB::beginTransaction();
    try {
      // GET CARTS
      $carts = auth()->user()->carts()->with(['product'])->whereIn('id', session('selectedCarts'))->get();

      // CREATE PAYMENT
      $payment = \App\Models\Payment::create([
        'status' => 'pending',
        'amount' => $this->totalPayment,
        'user_id' => auth()->id(),
      ]);

      // GET UNIQUE STORE IDs FROM RELATED PRODUCTS
      $storeIds = $carts->filter(function ($cart) {
        return $cart->product && $cart->product->store_id; // Ensure product exists
      })->pluck('product.store_id')->unique()->toArray();

      foreach ($storeIds as $storeId) {
        // GET STORE ITEMS
        $storeCarts = $carts->filter(function ($cart) use ($storeId) {
          return $cart->product && $cart->product->store_id == $storeId;
        });

        $totalStoreAmount = $storeCarts->sum(function ($cart) {
          return $cart->product->price * $cart->quantity;
        });

        // CREATE STORE TX
        $tx = \App\Models\Transaction::create([
          'status' => 'unprocessed',
          'amount' => $totalStoreAmount,
          'customer_name' => auth()->user()->name,
          'customer_email' => auth()->user()->email,
          'customer_phone' => auth()->user()->phone,
          'user_id' => auth()->id(),
          'store_id' => $storeId,
          'payment_id' => $payment->id
        ]);
        $tx->save();

        foreach ($storeCarts as $row) {
          $tx->details()->create([
            'product_id' => $row->product_id,
            'price' => $row->product->price,
            'quantity' => $row->quantity,
            'subtotal' => $row->product->price * $row->quantity,
            'note' => $row->note,
            'status' => $tx->status,
            'store_id' => $row->product->store_id
          ]);
        }
        transactionActivity($tx, auth()->id(), 'unprocessed', (auth()->user()->name . ' created transaction'));

        // CONFIRM BY SYSTEM
        // $payment->status = 'settlement';
        // $payment->settlement_at = now();
        // $payment->save();
        // $tx->status = 'confirmed';
        // $tx->save();
        // $tx->details()->update([
        //   'status' => $tx->status
        // ]);
        // transactionActivity($tx, auth()->id(), 'confirmed', ('confirmed by system'));
        // END CONFIRM BY SYSTEM

        $items = $tx->details->map(function ($item) {
          return [
            'sku' => 'P' . $item->product->id,
            'name' => $item->product->title,
            'price' => $item->price,
            'quantity' => $item->quantity,
            'product_url' => url($item->product->slug),
            'image_url' => productImage($item->product->mainImage())
          ];
        })->toArray();

        $items[] = [
          'sku' => 'FEE',
          'name' => 'Biaya Layanan',
          'price' => $this->platformFee,
          'quantity' => 1,
        ];

        if ($this->taxFee > 0) {
          $items[] = [
            'sku' => 'TAX',
            'name' => 'PPN',
            'price' => $this->taxFee,
            'quantity' => 1,
          ];
        }

        $tripay = tripay()->createTransaction([
          'method' => $this->selectedPayment,
          'merchant_ref' => $payment->id,
          'amount' => $this->totalPayment,
          'customer_name' => auth()->user()->name,
          'customer_email' => auth()->user()->email,
          'customer_phone' => auth()->user()->phone,
          'order_items' => $items,
          'return_url' => url('user/transaction?tab=confirmed'),
          'expired_time' => null, // Default 24 hours
        ]);

        if ($tripay['status'] === false) {
          throw new \Exception($tripay['data']);
        }

        $payment->data = $tripay['data']['data'];
        $payment->save();

        // update fee merchant
        $feeData = tripay()->calculateFee($this->selectedPayment, $this->totalPayment)['data'];
        $feeMerchant = $feeData[0]['total_fee']['merchant'];
        $payment->fee_amount = $feeMerchant;
        $payment->fee_wrap_up = ($this->totalPayment - $payment->transactions()->sum('amount')) - $feeMerchant;
        $payment->fee_total = $payment->fee_amount + $payment->fee_wrap_up;
        $payment->save();
      }
      auth()->user()->carts()->whereIn('id', session('selectedCarts'))->delete();
      session()->forget('selectedCarts');
      \DB::commit();
      $this->dispatch('alert-success', message: 'Transaksi berhasil dibuat.');
      $this->redirect('user/transaction', navigate: true);
      $this->redirect('payment/' . $payment->id, navigate: true);
    } catch (\Throwable $th) {
      \DB::rollBack();
      $this->dispatch('alert-error', message: 'Transaksi gagal.');
    }
  }
  public function selectPayment($code)
  {
    $this->selectedPayment = $code;
    // $selectedChannel = array_filter($this->channels, function ($item) use ($code) {
    //   return $item['code'] === $code;
    // });
    // $selectedChannel = reset($selectedChannel);
    $feeData = tripay()->calculateFee($code, $this->totalPayment)['data'];
    $feeMerchant = $feeData[0]['total_fee']['merchant'];
    $this->platformFee = ceil($feeMerchant);
    if ($code === 'QRIS2' || $code === 'QRIS') {
      $this->platformFee = $this->platformFee + (($this->productFee * config('services.platform.fee')) / 100);
    } else {
      // $wrapupFee = (($this->productFee * config('services.platform.fee')) / 100);
      // if ($wrapupFee > 5000) {
      //   $this->platformFee = $this->platformFee + 5000;
      // } else {
      //   $this->platformFee = $this->platformFee + (($this->productFee * config('services.platform.fee')) / 100);
      // }
      $this->platformFee = $this->platformFee + (($this->productFee * config('services.platform.fee')) / 100);
    }
    $this->reloadTotalPayment();
  }
  // END ACTIONS

  public function render()
  {
    $availableCarts = \App\Models\Cart::whereIn('id', session('selectedCarts'))->get();
    return view('livewire.checkout', compact('availableCarts'));
  }
}
