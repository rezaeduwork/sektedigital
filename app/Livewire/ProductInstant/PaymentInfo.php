<?php

namespace App\Livewire\ProductInstant;

use Livewire\Component;
use Livewire\Attributes\On;

class PaymentInfo extends Component
{
  public $product;
  public $address;
  public $selectedPayment;
  public $taxFee = 0;
  public $platformFee = 0;
  public $productFee = 0;
  public $totalPayment = 0;
  public $channels = [];
  public function mount($product)
  {
    $this->product = $product;
  }
  #[On('reload-payment.{product.id}')]
  public function reloadCryptoPayment($address, $amount)
  {
    if ($amount > 0) {
      $this->selectedPayment = null;
      $this->platformFee = 0;
      $this->taxFee = 0;
      $this->totalPayment = 0;
      $this->productFee = $amount;
      $this->getChannels();
    }
    if ($address) {
      $this->address = $address;
    }
  }
  public function getChannels()
  {
    $channelsData = tripay()->getPaymentChannels();
    if ($channelsData) {
      $this->channels = $channelsData['data'];
    }
  }
  // ACTIONS
  public function reloadTotalPayment()
  {
    $this->totalPayment = $this->taxFee + $this->platformFee + $this->productFee;
  }
  public function pay()
  {
    if (!$this->totalPayment > 0) {
      $this->dispatch('alert-error', message: 'Pembayaran gagal di proses, silahkan refresh halaman!');
      return false;
    }
    $this->dispatch('alert-success', message: 'Pembayaran sedang di proses');
    \DB::beginTransaction();
    try {
      // CREATE PAYMENT
      $payment = \App\Models\Payment::create([
        'status' => 'pending',
        'amount' => $this->totalPayment,
        'user_id' => auth()->id(),
      ]);

      $bnbidr = \App\Models\Currency::where('symbol', 'bnbidr')->first();

      // CREATE TRANSACTION
      $transaction = \App\Models\TransactionSingle::create([
        'status' => 'unprocessed',
        'amount' => $this->productFee,
        'customer_name' => auth()->user()->name,
        'product_name' => $this->product->title,
        'customer_email' => auth()->user()->email,
        'customer_phone' => auth()->user()->phone,
        'user_id' => auth()->user()->id,
        'payment_id' => $payment->id,
        'quantity' => round($this->totalPayment / $bnbidr->first()->price, 8),
        'data' => json_encode(['address' => $this->address, 'product_category' => $this->product->category])
      ]);

      $items = [
        [
          'sku' => 'PI' . $this->product->id,
          'name' => $this->product->title,
          'price' => $this->productFee,
          'quantity' => 1,
          'product_url' => url('i/' . $this->product->code),
          'image_url' => url($this->product->image)
        ]
      ];

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
      $data = $tripay['data']['data'];
      $payment->data = $data;

      $payment->save();

      // update fee merchant
      $feeData = tripay()->calculateFee($this->selectedPayment, $this->totalPayment)['data'];
      $feeMerchant = $feeData[0]['total_fee']['merchant'];
      $payment->fee_amount = $feeMerchant;
      $payment->fee_wrap_up = ($this->totalPayment - $payment->transactions()->sum('amount')) - $feeMerchant;
      $payment->fee_total = $payment->fee_amount + $payment->fee_wrap_up;
      $payment->save();

      \DB::commit();
      $this->dispatch('alert-success', message: 'Transaksi berhasil dibuat.');
      $this->redirect(url('payment/' . $payment->id), navigate: true);
    } catch (\Throwable $th) {
      \DB::rollBack();
      $this->dispatch('alert-error', message: 'Transaksi gagal.');
    }
  }
  public function selectPayment($code)
  {
    $this->selectedPayment = $code;
    $feeData = tripay()->calculateFee($code, $this->productFee)['data'];
    $feeMerchant = $feeData[0]['total_fee']['merchant'];
    $this->platformFee = ceil($feeMerchant);
    if ($code === 'QRIS2' || $code === 'QRIS') {
      $this->platformFee = $this->platformFee + (($this->productFee * config('services.platform.fee')) / 100);
    } else {
      $this->platformFee = $this->platformFee + (($this->productFee * config('services.platform.fee')) / 100);
    }
    $this->reloadTotalPayment();
  }
  // END ACTIONS
  public function render()
  {
    return view('livewire.product-instant.payment-info');
  }
}
