<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Payment as PaymentModel;
use App\Services\Tripay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Payment extends Component
{
  public $reference;
  public $payment;
  public $paymentData;
  public $paymentStatus;
  public $paymentAmount;
  public $paymentMethod;
  public $paymentExpiry;
  public $paymentInstructions;
  public $polling = true;

  public function mount($reference = null)
  {
    if (!$reference) {
      return redirect()->route('wallet');
    }

    $this->reference = $reference;
    $this->loadPaymentDetails();

    // If payment is already settled or failed, no need for polling
    if (in_array($this->paymentStatus, ['settlement', 'paid', 'failed', 'expired', 'cancelled'])) {
      $this->polling = false;
    }
  }

  public function loadPaymentDetails()
  {
    // Find payment by token/reference
    $this->payment = PaymentModel::where('token', $this->reference)->first();

    if (!$this->payment) {
      session()->flash('error', 'Pembayaran tidak ditemukan.');
      return redirect()->route('wallet');
    }

    // Check if payment belongs to current user
    if ($this->payment->user_id !== Auth::id()) {
      session()->flash('error', 'Anda tidak memiliki akses ke pembayaran ini.');
      return redirect()->route('wallet');
    }

    // Set payment status
    $this->paymentStatus = $this->payment->status;
    $this->paymentAmount = $this->payment->amount;

    // Parse payment data
    if ($this->payment->data) {
      $data = $this->payment->data;
      $this->paymentData = $data;

      if (isset($data['transaction_data']['data'])) {
        $transactionData = $data['transaction_data']['data'];
        $this->paymentMethod = $transactionData['payment_method'];
        $this->paymentExpiry = $transactionData['expired_time'] ?? null;
        $this->paymentInstructions = $transactionData['instructions'] ?? [];
      }
    }
  }

  public function checkPaymentStatus()
  {
    if (!$this->reference || !$this->payment) {
      return;
    }

    $tripay = new Tripay();
    $response = $tripay->checkTransactionDetail($this->reference);

    if ($response['status']) {
      $status = $response['data']['data']['status'];

      // Update payment status if it has changed
      if ($status === 'PAID' && $this->payment->status !== 'settlement') {
        $this->payment->update([
          'status' => 'settlement',
          'settlement_at' => now(),
        ]);

        $this->paymentStatus = 'settlement';
        $this->processDepositSuccess();
        $this->polling = false;

        session()->flash('success', 'Pembayaran berhasil! Saldo telah ditambahkan ke akun Anda.');
      } else if (in_array($status, ['EXPIRED', 'FAILED', 'CANCELLED']) && $this->payment->status === 'pending') {
        $this->payment->update([
          'status' => strtolower($status),
        ]);

        $this->paymentStatus = strtolower($status);
        $this->polling = false;

        session()->flash('error', 'Pembayaran ' . strtolower($status) . '.');
      }
    }
  }

  // Process successful deposit locally without waiting for webhook
  protected function processDepositSuccess()
  {
    if (!Auth::user() || $this->payment->transaction_type !== 'deposit') {
      return;
    }

    try {
      // Only create balance entry if payment was just settled
      if ($this->payment->status === 'settlement' && !Auth::user()->balances()->where('description', 'LIKE', '%' . $this->payment->id . '%')->exists()) {
        // Create a UserBalance record for the deposit
        Auth::user()->balances()->create([
          'uid' => Auth::id() . uniqid(),
          'type' => 'store_fund',
          'amount' => $this->payment->amount,
          'description' => 'Deposit saldo pada ' . now()->translatedFormat('l, d F Y H:i') . ' (ID: ' . $this->payment->id . ')',
          'status' => 'success',
          'name' => 'Deposit Saldo'
        ]);

        // Reload user balance
        Auth::user()->reloadBalance();

        Log::info('Deposit processed manually in Payment component for user #' . Auth::id() . ', amount: ' . $this->payment->amount);
      }
    } catch (\Exception $e) {
      Log::error('Manual deposit processing error: ' . $e->getMessage());
    }
  }

  public function render()
  {
    return view('livewire.user.payment')->layout('components.layouts.app-dashboard');
  }
}
