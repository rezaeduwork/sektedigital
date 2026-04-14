<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Payment as PaymentModel;
use App\Services\Gateways\GatewayFactory;
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
  public $paymentGateway;
  public $polling = false;

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
    $this->paymentGateway = $this->payment->payment_gateway ?? 'tripay';

    // Parse payment data based on gateway
    if ($this->payment->data) {
      $data = $this->payment->data;
      $this->paymentData = $data;

      // Handle different gateway response structures
      $this->extractPaymentDetails($data);
    }
  }

  /**
   * Extract payment details based on gateway type
   */
  protected function extractPaymentDetails($data)
  {
    // Log the data structure for debugging
    Log::info('Extracting payment details for gateway: ' . $this->paymentGateway);
    Log::info('Payment data structure: ' . json_encode($data));

    switch ($this->paymentGateway) {
      case 'tripay':
        if (isset($data['transaction_data']['data'])) {
          $transactionData = $data['transaction_data']['data'];
          $this->paymentMethod = $transactionData['payment_method'] ?? '-';
          $this->paymentExpiry = $transactionData['expired_time'] ?? null;
          $this->paymentInstructions = $transactionData['instructions'] ?? [];
        }
        break;

      case 'xendit':
        $transactionData = $data['transaction_data'] ?? [];
        $this->paymentMethod = $transactionData['payment_method'] ?? $transactionData['channel_code'] ?? '-';
        $this->paymentExpiry = isset($transactionData['expiration_date']) ? strtotime($transactionData['expiration_date']) : null;
        $this->paymentInstructions = [];
        break;

      case 'paymenku':
        if (isset($data['transaction_data']['data'])) {
          $transactionData = $data['transaction_data']['data'];
          $this->paymentMethod = $transactionData['payment_channel']['name'] ?? $transactionData['payment_channel']['code'] ?? '-';
          $this->paymentExpiry = isset($transactionData['created_at']) ? strtotime($transactionData['created_at']) + (24 * 3600) : null;
          $this->paymentInstructions = [];
        }
        break;

      case 'sakurupiah':
        // SakuRupiah stores the response directly in transaction_data
        $transactionData = $data['transaction_data'] ?? [];

        // Extract method from via or payment_kode
        $this->paymentMethod = $transactionData['via'] ?? $transactionData['payment_kode'] ?? '-';

        // Parse expiry datetime
        $this->paymentExpiry = $this->payment->created_at ? strtotime($this->payment->created_at) : null;

        $this->paymentInstructions = [];

        break;

      default:
        // Generic fallback
        $transactionData = $data['transaction_data'] ?? [];
        $this->paymentMethod = $transactionData['payment_method'] ?? $transactionData['method'] ?? '-';
        $this->paymentExpiry = $transactionData['expired_time'] ?? null;
        $this->paymentInstructions = $transactionData['instructions'] ?? [];
        break;
    }

    Log::info('Extracted - Method: ' . $this->paymentMethod . ', Expiry: ' . $this->paymentExpiry);
  }

  public function checkPaymentStatus()
  {
    if (!$this->reference || !$this->payment || !$this->paymentGateway) {
      return;
    }

    try {
      // Get the appropriate gateway instance
      $gateway = GatewayFactory::findByGatewayName($this->paymentGateway);

      if (!$gateway) {
        Log::warning('Gateway not found or inactive: ' . $this->paymentGateway);
        return;
      }

      // Check transaction detail
      $response = $gateway->checkTransactionDetail($this->reference);

      if ($response['status']) {
        // Extract status based on gateway
        $status = $this->normalizePaymentStatus($response['data'], $this->paymentGateway);

        // Update payment status if it has changed
        if (in_array($status, ['paid', 'settlement', 'success']) && $this->payment->status !== 'settlement') {
          $this->payment->update([
            'status' => 'settlement',
            'settlement_at' => now(),
          ]);

          $this->paymentStatus = 'settlement';
          $this->processDepositSuccess();
          $this->polling = false;

          session()->flash('success', 'Pembayaran berhasil! Saldo telah ditambahkan ke akun Anda.');
        } else if (in_array($status, ['expired', 'failed', 'cancelled']) && $this->payment->status === 'pending') {
          $this->payment->update([
            'status' => strtolower($status),
          ]);

          $this->paymentStatus = strtolower($status);
          $this->polling = false;

          session()->flash('error', 'Pembayaran ' . strtolower($status) . '.');
        }
      }
    } catch (\Exception $e) {
      Log::error('Check payment status error: ' . $e->getMessage());
    }
  }

  /**
   * Normalize payment status from different gateways
   */
  protected function normalizePaymentStatus($data, $gateway)
  {
    switch ($gateway) {
      case 'tripay':
        return strtolower($data['data']['status'] ?? 'pending');

      case 'xendit':
        $status = strtolower($data['status'] ?? 'pending');
        // Xendit uses PAID, EXPIRED, etc.
        return $status === 'paid' ? 'settlement' : $status;

      case 'paymenku':
        $status = strtolower($data['data']['status'] ?? 'pending');
        return $status === 'paid' ? 'settlement' : $status;

      case 'sakurupiah':
        // SakuRupiah uses payment_status field
        $status = strtolower($data['payment_status'] ?? 'pending');
        return $status === 'paid' || $status === 'success' ? 'settlement' : $status;

      default:
        return strtolower($data['status'] ?? 'pending');
    }
  }

  /**
   * Get live payment detail from gateway
   */
  public function getPaymentDetail()
  {
    if (!$this->payment || !$this->paymentGateway || !$this->reference) {
      return null;
    }

    try {
      $gateway = GatewayFactory::findByGatewayName($this->paymentGateway);

      if (!$gateway) {
        return null;
      }

      $result = $gateway->checkTransactionDetail($this->reference);

      return $result['status'] ? $result : null;
    } catch (\Exception $e) {
      Log::error('Error fetching payment detail: ' . $e->getMessage());
      return null;
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
