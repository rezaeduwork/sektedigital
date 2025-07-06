<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\Tripay;

class Wallet extends Component
{
  public $page;
  public $ref_id;
  public $amount;
  public $minWithdraw = 25000;

  // Deposit properties
  public $depositAmount;
  public $paymentMethod;
  public $minDeposit = 50000;
  public $paymentMethods = [];

  public function mount()
  {
    $this->loadPaymentMethods();
  }

  public function loadPaymentMethods()
  {
    $tripay = tripay();
    $channels = $tripay->getPaymentChannels();

    if ($channels && isset($channels['data'])) {
      // Filter for common payment methods
      $this->paymentMethods = collect($channels['data'])
        ->filter(function ($method) {
          return in_array($method['group'], ['Virtual Account', 'QRIS', 'QRIS2']) ||
            in_array($method['code'], ['QRIS', 'QRIS2']);
        })
        ->toArray();
    }
  }
  public function updatedAmount()
  {
    if ($this->amount > auth()->user()->balance) {
      $this->amount = auth()->user()->balance;
    }
  }
  public function withdraw()
  {
    if (!$this->ref_id) {
      $this->dispatch('alert-error', message: "Gagal, silahkan pilih rekening penarikan.");
      return;
    }
    if ($this->amount === null || !is_numeric($this->amount) || $this->amount < 0) {
      $this->dispatch('alert-error', message: "Gagal, silahkan isi jumlah penarikan.");
      return;
    }
    if ($this->amount < $this->minWithdraw) {
      $this->dispatch('alert-error', message: "Gagal, minimal penarikan " . number_format($this->minWithdraw) . ".");
      return;
    }
    if ($this->amount > auth()->user()->balance) {
      $this->dispatch('alert-error', message: "Gagal, jumlah penarikan melebihi batas.");
      return;
    }
    auth()->user()->balances()->create([
      'uid' => auth()->id() . uniqid(),
      'type' => 'withdraw',
      'amount' => -abs($this->amount),
      'description' => 'Request withdrawal pada ' . now()->translatedFormat('l, d F Y H:i'),
      'status' => 'pending',
      'name' => 'Withdraw Fund'
    ]);
    auth()->user()->reloadBalance();
    $this->dispatch('alert-success', message: "Berhasil request penarikan silahkan tunggu 3x24 jam untuk di proses!");
    $this->dispatch('reload')->to(\App\Livewire\User\Wallet\History::class);
    $this->resetWithdrawalAttr();
  }
  public function resetWithdrawalAttr()
  {
    $this->ref_id = null;
    $this->amount = null;
  }

  public function reloadUserBalance()
  {
    $user = Auth::user();
    $user->reloadBalance();
    $this->dispatch('alert-success', message: "Saldo berhasil diperbarui!");
    return;
  }

  // Deposit functionality
  public function initiateDeposit()
  {
    $this->validate([
      'depositAmount' => 'required|numeric|min:' . $this->minDeposit,
      'paymentMethod' => 'required',
    ], [
      'depositAmount.min' => 'Minimal deposit adalah Rp ' . number_format($this->minDeposit) . '.',
      'depositAmount.required' => 'Jumlah deposit wajib diisi.',
      'paymentMethod.required' => 'Metode pembayaran wajib dipilih.'
    ]);

    $user = Auth::user();
    $totalAmount = $this->depositAmount;

    // First create a payment record in the database
    $payment = Payment::create([
      'status' => 'pending',
      'amount' => $totalAmount,
      'user_id' => $user->id,
      'transaction_type' => 'deposit',
      'expired_at' => now()->addHours(24)
    ]);

    // Create unique reference using the payment ID
    $merchantRef = 'DEPOSIT-' . $payment->id;

    // Create order item for Tripay
    $orderItems = [
      [
        'name' => 'Deposit Saldo',
        'price' => $totalAmount,
        'quantity' => 1,
      ]
    ];

    // Prepare transaction data
    $transactionData = [
      'method' => $this->paymentMethod,
      'merchant_ref' => $merchantRef,
      'amount' => $totalAmount,
      'customer_name' => $user->name,
      'customer_email' => $user->email,
      'customer_phone' => $user->phone ?? '08123456789',
      'order_items' => $orderItems,
      'return_url' => url('user/wallet'),
      'expired_time' => (time() + (24 * 60 * 60)), // 24 hours
    ];

    // Call Tripay service to create transaction
    $tripay = new Tripay();
    $transaction = $tripay->createTransaction($transactionData);

    if ($transaction['status']) {
      // Format payment data for webhook processing
      $paymentData = [
        'transaction_data' => $transaction['data'],
        'merchant_ref' => $merchantRef,
      ];

      // Update the payment record with transaction details
      $payment->update([
        'token' => $transaction['data']['data']['reference'],
        'data' => json_encode($paymentData),
      ]);

      // Redirect to our custom payment page
      $reference = $transaction['data']['data']['reference'];
      return redirect()->route('payment.detail', ['reference' => $reference]);
    } else {
      $this->dispatch('alert-error', message: 'Gagal membuat transaksi: ' . $transaction['data']);
    }
  }

  #[On('reload')]
  public function render()
  {
    return view('livewire.user.wallet');
  }
}
