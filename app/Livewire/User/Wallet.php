<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\Gateways\GatewayFactory;

class Wallet extends Component
{
  public $page;
  public $ref_id;
  public $amount;
  public $minWithdraw = 25000;

  // Deposit properties
  public $depositAmount = 50000;
  public $paymentMethod;
  public $selectedGatewayId; // Store which gateway the selected method belongs to
  public $minDeposit = 50000;
  public $paymentMethods = [];

  public function mount()
  {
    $this->loadPaymentMethods();
  }

  public function loadPaymentMethods()
  {
    try {
      // Get payment methods from all active gateways
      $allMethods = GatewayFactory::getAllPaymentMethods();

      // Filter and format payment methods
      $this->paymentMethods = collect($allMethods)
        ->filter(function ($method) {
          // Filter for common payment methods based on different gateway formats
          // Tripay uses 'group', others might use 'type'
          $group = $method['group'] ?? $method['type'] ?? $method['metode'] ?? '';
          $code = $method['code'] ?? $method['kode'] ?? '';

          return in_array($group, ['QRIS2']) ||
            in_array($code, ['QRIS2']);
        })
        ->map(function ($method) {
          $group = $method['group'] ?? $method['type'] ?? $method['metode'] ?? '';
          $code = $method['code'] ?? $method['kode'] ?? '';
          // Normalize the data structure
          return [
            'code' => $code,
            'name' => $method['name'] ?? $method['nama'] ?? '',
            'group' => $group,
            'fee_flat' => $method['total_fee']['flat'] ?? $method['fee']['flat'] ?? 0,
            'fee_percent' => $method['total_fee']['percent'] ?? $method['fee']['percent'] ?? 0,
            'gateway_id' => $method['gateway_id'] ?? null,
            'gateway_name' => $method['gateway_name'] ?? '',
            'gateway_display_name' => $method['gateway_display_name'] ?? '',
            'icon' => $method['icon_url'] ?? $method['icon'] ?? '',
          ];
        })
        ->toArray();
    } catch (\Exception $e) {
      \Log::error('Failed to load payment methods: ' . $e->getMessage());
      $this->paymentMethods = [];
    }
  }
  // When payment method is selected, store the gateway ID
  public function updatedPaymentMethod($value)
  {
    $selectedMethod = collect($this->paymentMethods)
      ->firstWhere('code', $value);

    if ($selectedMethod) {
      $this->selectedGatewayId = $selectedMethod['gateway_id'];
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

    // Get the gateway instance for the selected payment method
    if (!$this->selectedGatewayId) {
      $this->dispatch('alert-error', message: 'Gateway tidak ditemukan untuk metode pembayaran ini.');
      return;
    }

    $gateway = GatewayFactory::findByGatewayId($this->selectedGatewayId);

    if (!$gateway) {
      $this->dispatch('alert-error', message: 'Gateway pembayaran tidak tersedia.');
      return;
    }

    $user = Auth::user();
    $totalAmount = $this->depositAmount;

    // First create a payment record in the database
    $payment = Payment::create([
      'status' => 'pending',
      'amount' => $totalAmount,
      'user_id' => $user->id,
      'transaction_type' => 'deposit',
      'payment_gateway' => $gateway->gateway->name,
      'expired_at' => now()->addHours(24)
    ]);

    // Create unique reference using the payment ID
    $merchantRef = 'DEPOSIT-' . $payment->id;

    // Prepare transaction data (normalized format for all gateways)
    $transactionData = [
      'method' => $this->paymentMethod,
      'merchant_ref' => $merchantRef,
      'amount' => $totalAmount,
      'customer_name' => $user->name,
      'customer_email' => $user->email,
      'customer_phone' => $user->phone ?? '08123456789',
      'order_items' => [[
        'name' => 'Deposit Saldo',
        'price' => $totalAmount,
        'quantity' => 1,
      ]],
      'return_url' => url('user/wallet'),
      'callback_url' => url('/webhook/' . $gateway->gateway->name),
      'expired_time' => time() + (24 * 60 * 60), // 24 hours
    ];

    // Call gateway service to create transaction
    try {
      $transaction = $gateway->createTransaction($transactionData);

      if ($transaction['status']) {
        // Extract reference based on gateway response structure
        $reference = $this->extractReference($transaction['data'], $gateway->gateway->name);
        // Format payment data for webhook processing
        $paymentData = [
          'transaction_data' => $transaction['data'],
          'merchant_ref' => $merchantRef,
          'gateway' => $gateway->gateway->name,
        ];

        // Update the payment record with transaction details
        $payment->update([
          'token' => $reference,
          'data' => $paymentData,
        ]);

        // Redirect to our custom payment page
        return redirect('/user/payment/' . $reference);
      } else {
        $errorMessage = $transaction['message'] ?? 'Gagal membuat transaksi';
        $this->dispatch('alert-error', message: $errorMessage);
      }
    } catch (\Exception $e) {
      \Log::error('Deposit transaction error: ' . $e->getMessage());
      $this->dispatch('alert-error', message: 'Terjadi kesalahan saat membuat transaksi.');
    }
  }

  /**
   * Extract reference from transaction response based on gateway
   *
   * @param array $data
   * @param string $gatewayName
   * @return string
   */
  private function extractReference(array $data, string $gatewayName): string
  {
    // Different gateways have different response structures
    switch ($gatewayName) {
      case 'tripay':
        return $data['data']['reference'] ?? '';
      case 'xendit':
        return $data['id'] ?? $data['external_id'] ?? '';
      case 'paymenku':
        return $data['data']['trx_id'] ?? '';
      case 'sakurupiah':
        // SakuRupiah: use trx_id from the response (data[0] already extracted in gateway)
        return $data['trx_id'] ?? $data['merchant_ref'] ?? '';
      default:
        return $data['reference'] ?? $data['id'] ?? '';
    }
  }

  #[On('reload')]
  public function render()
  {
    return view('livewire.user.wallet');
  }
}
