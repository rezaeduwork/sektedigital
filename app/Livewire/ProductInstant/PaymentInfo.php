<?php

namespace App\Livewire\ProductInstant;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class PaymentInfo extends Component
{
  public $product;

  // CRYPTO
  public $address;

  // PULSA DATA
  #[Url]
  public ?string $provider;
  #[Url]
  public ?string $phone;
  public $informations = [];

  public $selectedPayment;
  public $taxFee = 0;
  public $platformFee = 0;
  public $productFee = 0;
  public $totalPayment = 0;
  public $channels = [];
  public function mount($product, $informations)
  {
    $this->product = $product;
    if (in_array($this->product->category, ['Pulsa', 'Data'])) {
      $this->reloadPaymentAttributes();
    } else {
      $this->reloadPaymentAttributes();
    }
    $this->informations = $informations;
  }
  #[On('reload-crypto-payment.{product.id}')]
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
  public function reloadPaymentAttributes()
  {
    $amount = $this->product->price;
    if ($amount > 0) {
      $this->selectedPayment = null;
      $this->platformFee = 0;
      $this->taxFee = 0;
      $this->totalPayment = 0;

      $this->productFee = $amount;
      $this->getChannels();
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
    $this->reloadTotalPayment();
    $digiflazz = new \App\Services\Digiflazz;
    $digiflazzBalance = $digiflazz->checkBalances();
    if ($digiflazzBalance['success'] === false) {
      $this->dispatch('alert-error', message: 'Gagal memproses pembayaran, silahkan hubungi admin.');
      return false;
    }
    if (config('app.env') == 'production') {
      if ($digiflazzBalance['data']['deposit'] < $this->totalPayment) {
        $this->dispatch('alert-error', message: 'Gagal memproses pembayaran, silahkan hubungi admin.');
        return false;
      }
    }
    if ($this->totalPayment <= 0) {
      $this->dispatch('alert-error', message: 'Pembayaran gagal di proses, silahkan refresh halaman!');
      return false;
    }
    $this->dispatch('alert-success', message: 'Pembayaran sedang di proses');
    \DB::beginTransaction();
    try {
      // CREATE PAYMENT
      $payment = \App\Models\Payment::create([
        'status' => 'pending',
        'transaction_type' => 'instant',
        'amount' => $this->totalPayment,
        'user_id' => auth()->id() ?: null,
      ]);

      $dataTx = $this->informations;
      $quantityTx = 1;
      if ($this->product->category == 'buy-crypto') {
        $bnbidr = \App\Models\Currency::where('symbol', 'bnbidr')->first();
        $dataTx = ['address' => $this->address, 'product_category' => $this->product->category];
        $quantityTx = round($this->totalPayment / $bnbidr->first()->price, 8);
      }

      $transactionData = [
        'status' => 'unprocessed',
        'amount' => $this->productFee,
        'product_name' => $this->product->title,
        'product_id' => $this->product->id,
        ...getRandomGuestDetail(),
        'user_id' => auth()->user() ? auth()->user()->id : null,
        'payment_id' => $payment->id,
        'quantity' => $quantityTx,
        'data' => json_encode($dataTx)
      ];

      // CREATE TRANSACTION
      $transaction = \App\Models\TransactionSingle::create($transactionData);

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
        ...getRandomGuestDetail(),
        'order_items' => $items,
        'return_url' => auth()->check() ? url('user/transaction?tab=confirmed') : url('payment/' . $payment->id . '/success'),
        'expired_time' => null, // Default 24 hours
      ]);

      if ($tripay['status'] === false) {
        throw new \Exception($tripay['data']);
      }
      $data = $tripay['data']['data'];
      if (isset($data['expired_time'])) {
        $payment->expired_at = \Carbon\Carbon::createFromTimestamp($data['expired_time']);
      }
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
      if (config('app.env') == 'local') {
        dd($th);
      }
      $this->dispatch('alert-error', message: 'Transaksi gagal.');
    }
  }
  public function selectPayment($code)
  {
    $this->selectedPayment = $code;
    $this->platformFee = platformFee($code, $this->productFee);
    $this->reloadTotalPayment();
  }
  // END ACTIONS
  public function render()
  {
    return view('livewire.product-instant.payment-info');
  }
}
