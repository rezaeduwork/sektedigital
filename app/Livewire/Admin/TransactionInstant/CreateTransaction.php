<?php

namespace App\Livewire\Admin\TransactionInstant;

use Livewire\Component;

class CreateTransaction extends Component
{
  public $category;
  public $brand;
  public $productId;
  public $product;
  public $informations = [];
  public $canSubmit = false;
  public $showConfirmation = false;
  public $selectedPayment;
  public $taxFee = 0;
  public $platformFee = 0;
  public $productFee = 0;
  public $totalPayment = 0;
  public function mount()
  {
    $this->informations = getTransactionInstantInformations($this->category, $this->brand);
  }
  public function updatedProductId()
  {
    $this->product = \App\Models\ProductInstant::find($this->productId);
  }
  public function fillAccount($index, $value)
  {
    if (sizeof($this->informations) == 0 && $this->category && $this->brand) {
      $this->informations = getTransactionInstantInformations($this->category, $this->brand);
    }
    if ($this->product) {
      $this->reloadPaymentAttributes();
      $this->reloadTotalPayment();
    }
    $this->informations[$index]['value'] = $value;
    $this->reloadAccount();
  }
  public function resetInput()
  {
    $this->product = null;
    $this->informations = [];
    $this->category = null;
    $this->brand = null;
    $this->productId = null;
    $this->canSubmit = false;
    $this->showConfirmation = false;
    $this->selectedPayment = null;
  }
  public function reloadAccount()
  {
    $infomationValues = collect($this->informations)->pluck('value')->toArray();
    if (sizeof($infomationValues) > 0) {
      $this->account = implode('', $infomationValues);
    } else {
      $this->account = null;
    }
    if ($this->product && $this->account) {
      $checkHasNullValue = collect($this->informations)->contains(function ($item) {
        return $item['value'] == null && ((isset($item['required']) && $item['required'] !== false) || !isset($item['required']));
      });
      if ($checkHasNullValue) {
        $this->canSubmit = false;
      } else {
        $this->canSubmit = true;
      }
    } else {
      $this->canSubmit = false;
    }
  }
  public function reloadTotalPayment()
  {
    $this->totalPayment = $this->taxFee + $this->platformFee + $this->productFee;
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
    }
  }
  public function process()
  {
    if (!$this->category || !$this->brand || !$this->productId) {
      $this->dispatch('alert-error', message: 'Lengkapi semua data yang dibutuhkan!');
      return false;
    }
    foreach ($this->informations as $key => $row) {
      if ((!isset($row['required']) || (isset($row['required']) && $row['required'] !== false)) && $row['value'] === null) {
        $this->dispatch('alert-error', message: $row['label'] . ' dibutuhkan!');
        return false;
      }
    }

    // CREATE PAYMENT
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
    // CREATE TRANSACTION
    \DB::beginTransaction();
    try {
      // CREATE PAYMENT
      $payment = \App\Models\Payment::create([
        'status' => 'settlement',
        'settlement_at' => now(),
        'transaction_type' => 'instant',
        'amount' => $this->totalPayment,
        'user_id' => auth()->id(),
      ]);

      $dataTx = $this->informations;
      $quantityTx = 1;
      if ($this->product->category == 'buy-crypto') {
        $bnbidr = \App\Models\Currency::where('symbol', 'bnbidr')->first();
        $dataTx = ['address' => $this->address, 'product_category' => $this->product->category];
        $quantityTx = round($this->totalPayment / $bnbidr->first()->price, 8);
      }

      $transactionData = [
        'status' => 'confirmed',
        'amount' => $this->productFee,
        'product_name' => $this->product->title,
        'product_id' => $this->product->id,
        ...getRandomGuestDetail(),
        'user_id' => null,
        'payment_id' => $payment->id,
        'quantity' => $quantityTx,
        'data' => json_encode($dataTx)
      ];

      // CREATE TRANSACTION
      $transaction = \App\Models\TransactionSingle::create($transactionData);

      if ($payment->singleTransaction->product->provider == 'digiflazz') {
        $data = digiflazz()->createTransaction($payment->singleTransaction);
        if ($data['success'] === true) {
          $payment->singleTransaction()->update(['status' => 'finished']);
          $this->dispatch('alert-success', message: 'Transaksi digiflazz sukses.');
        } else {
          if ($data['data']['status'] == 'Pending') {
            $payment->singleTransaction()->update(['status' => 'confirmed']);
            $this->dispatch('alert-success', message: 'Transaksi digiflazz pending.');
          } else {
            $payment->singleTransaction()->update(['status' => 'rejected']);
            $this->dispatch('alert-error', message: 'Transaksi digiflazz gagal.');
          }
        }
      }
      $this->resetInput();
      \DB::commit();
    } catch (\Throwable $th) {
      \DB::rollBack();
      if (config('app.env') == 'local') {
        dd($th);
      }
      $this->dispatch('alert-error', message: 'Transaksi gagal.');
    }

    $this->dispatch('reload')->to(\App\Livewire\Admin\TransactionInstant::class);
    $this->dispatch('reload')->self();
  }
  public function render()
  {
    return view('livewire.admin.transaction-instant.create-transaction');
  }
}
