<?php

namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;

class XenditGateway extends BaseGateway
{
  protected function setBaseUrl(): void
  {
    $this->baseUrl = 'https://api.xendit.co/';
  }

  public function getPaymentChannels(): ?array
  {
    try {
      // Xendit doesn't have a single endpoint for all channels
      // Return available payment methods
      return [
        'success' => true,
        'data' => [
          ['code' => 'EWALLET', 'name' => 'E-Wallet (OVO, DANA, LinkAja, ShopeePay)'],
          ['code' => 'VIRTUAL_ACCOUNT', 'name' => 'Virtual Account'],
          ['code' => 'RETAIL_OUTLET', 'name' => 'Retail Outlet (Alfamart, Indomaret)'],
          ['code' => 'QRIS', 'name' => 'QRIS'],
          ['code' => 'CREDIT_CARD', 'name' => 'Credit Card'],
        ]
      ];
    } catch (\Exception $e) {
      \Log::error('Xendit Payment Channels Error: ' . $e->getMessage());
      return null;
    }
  }

  public function calculateFee(string $paymentMethod, int $amount): ?array
  {
    // Xendit fees vary by payment method
    // This is a simplified example
    $fees = [
      'EWALLET' => ['percentage' => 2, 'fixed' => 0],
      'VIRTUAL_ACCOUNT' => ['percentage' => 0, 'fixed' => 4000],
      'RETAIL_OUTLET' => ['percentage' => 0, 'fixed' => 5000],
      'QRIS' => ['percentage' => 0.7, 'fixed' => 0],
      'CREDIT_CARD' => ['percentage' => 2.9, 'fixed' => 2000],
    ];

    $feeConfig = $fees[$paymentMethod] ?? ['percentage' => 0, 'fixed' => 0];
    $calculatedFee = ($amount * $feeConfig['percentage'] / 100) + $feeConfig['fixed'];

    return [
      'success' => true,
      'data' => [
        'fee' => (int) $calculatedFee,
        'total_amount' => $amount + (int) $calculatedFee,
      ]
    ];
  }

  public function createTransaction(array $data): array
  {
    try {
      $apiKey = $this->getConfig('api_key');

      // Create invoice
      $payload = [
        'external_id' => $data['merchant_ref'],
        'amount' => $data['amount'],
        'payer_email' => $data['customer_email'],
        'description' => $data['description'] ?? 'Payment',
        'invoice_duration' => $data['invoice_duration'] ?? 86400, // 24 hours in seconds
        'success_redirect_url' => $data['return_url'] ?? url('/'),
        'failure_redirect_url' => $data['return_url'] ?? url('/'),
      ];

      // Add customer data if available
      if (isset($data['customer_name'])) {
        $payload['customer'] = [
          'given_names' => $data['customer_name'],
          'email' => $data['customer_email'],
          'mobile_number' => $data['customer_phone'] ?? '',
        ];
      }

      $response = Http::withBasicAuth($apiKey, '')
        ->post($this->baseUrl . 'v2/invoices', $payload);

      if ($response->successful()) {
        return [
          'status' => true,
          'data' => $response->json(),
          'gateway' => 'xendit'
        ];
      } else {
        return [
          'status' => false,
          'data' => $response->json(),
          'message' => 'Xendit Transaction Error: ' . $response->body(),
          'gateway' => 'xendit'
        ];
      }
    } catch (\Exception $e) {
      return [
        'status' => false,
        'data' => [],
        'message' => 'Xendit Transaction Error: ' . $e->getMessage(),
        'gateway' => 'xendit'
      ];
    }
  }

  public function checkTransactionDetail(string $reference): array
  {
    try {
      $apiKey = $this->getConfig('api_key');

      // Get invoice by external ID
      $response = Http::withBasicAuth($apiKey, '')
        ->get($this->baseUrl . 'v2/invoices', [
          'external_id' => $reference
        ]);

      if ($response->successful()) {
        $invoices = $response->json();
        $invoice = $invoices[0] ?? null;

        if ($invoice) {
          return [
            'status' => true,
            'data' => $invoice,
            'gateway' => 'xendit'
          ];
        } else {
          return [
            'status' => false,
            'data' => [],
            'message' => 'Invoice not found',
            'gateway' => 'xendit'
          ];
        }
      } else {
        return [
          'status' => false,
          'data' => $response->json(),
          'message' => 'Xendit Transaction Detail Error: ' . $response->body(),
          'gateway' => 'xendit'
        ];
      }
    } catch (\Exception $e) {
      return [
        'status' => false,
        'data' => [],
        'message' => 'Xendit Transaction Detail Error: ' . $e->getMessage(),
        'gateway' => 'xendit'
      ];
    }
  }

  /**
   * Create Virtual Account
   *
   * @param array $data
   * @return array
   */
  public function createVirtualAccount(array $data): array
  {
    try {
      $apiKey = $this->getConfig('api_key');

      $payload = [
        'external_id' => $data['merchant_ref'],
        'bank_code' => $data['bank_code'], // BCA, BNI, BRI, MANDIRI, PERMATA
        'name' => $data['customer_name'],
        'expected_amount' => $data['amount'],
      ];

      $response = Http::withBasicAuth($apiKey, '')
        ->post($this->baseUrl . 'callback_virtual_accounts', $payload);

      if ($response->successful()) {
        return [
          'status' => true,
          'data' => $response->json(),
          'gateway' => 'xendit'
        ];
      } else {
        return [
          'status' => false,
          'data' => $response->json(),
          'message' => 'Xendit VA Creation Error: ' . $response->body(),
          'gateway' => 'xendit'
        ];
      }
    } catch (\Exception $e) {
      return [
        'status' => false,
        'data' => [],
        'message' => 'Xendit VA Creation Error: ' . $e->getMessage(),
        'gateway' => 'xendit'
      ];
    }
  }
}
