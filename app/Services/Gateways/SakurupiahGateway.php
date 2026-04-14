<?php

namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;

class SakurupiahGateway extends BaseGateway
{
  protected function setBaseUrl(): void
  {
    $this->baseUrl = $this->isProduction()
      ? 'https://sakurupiah.id/api'
      : 'https://sakurupiah.id/api-sanbox'; // Note: It's "sanbox" not "sandbox" (their typo)
  }

  public function getPaymentChannels(): ?array
  {
    try {
      $url = $this->baseUrl . '/list-payment.php';

      \Log::info('SakuRupiah getPaymentChannels URL: ' . $url);
      \Log::info('SakuRupiah API ID: ' . $this->getConfig('api_id'));

      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key'),
        'Content-Type' => 'application/json'
      ])->post($url, [
        'api_id' => $this->getConfig('api_id'),
        'method' => 'list'
      ]);

      \Log::info('SakuRupiah Response Status: ' . $response->status());

      if ($response->successful()) {
        return $response->json();
      }

      // Log error details for debugging
      \Log::error('SakuRupiah Payment Channels Error - Status: ' . $response->status());
      \Log::error('SakuRupiah Payment Channels Error - Body: ' . $response->body());

      return null;
    } catch (\Exception $e) {
      \Log::error('SakuRupiah Payment Channels Exception: ' . $e->getMessage());
      \Log::error('SakuRupiah Payment Channels Exception Trace: ' . $e->getTraceAsString());
      return null;
    }
  }

  public function calculateFee(string $paymentMethod, int $amount): ?array
  {
    // Note: Update this endpoint based on SakuRupiah's actual fee calculation API
    // For now, returning null as the endpoint is not confirmed
    \Log::info('SakuRupiah fee calculation endpoint not implemented');
    return null;
  }

  public function createTransaction(array $data): array
  {
    try {
      $apiId = $this->getConfig('api_id');
      $method = $data['method'];
      $merchantRef = $data['merchant_ref'];
      $amount = $data['amount'];
      $apiKey = $this->getConfig('api_key');
      // dd([$apiId, $method, $merchantRef, $amount, $apiKey]);
      // Generate signature using helper method
      $signature = $this->generateSignature($apiId, $method, $merchantRef, $amount, $apiKey);

      $payload = [
        'api_id' => $apiId,
        'method' => $method,
        'name' => $data['customer_name'],
        'email' => $data['customer_email'],
        'phone' => $data['customer_phone'] ?? '',
        'amount' => $amount,
        'merchant_fee' => $data['merchant_fee'] ?? '1',
        'merchant_ref' => $merchantRef,
        'expired' => $data['expired'] ?? '24',
        'produk' => $data['order_items'] ?? ['produk 1'],
        'qty' => $data['qty'] ?? [1],
        'harga' => $data['harga'] ?? [$amount],
        'size' => $data['size'] ?? ['XL'],
        'note' => $data['note'] ?? [''],
        'callback_url' => $data['callback_url'] ?? url('/webhook/sakurupiah'),
        'return_url' => $data['return_url'] ?? url('/'),
        'signature' => $signature,
      ];

      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
      ])->post($this->baseUrl . '/create.php', $payload);

      if ($response->successful()) {
        $result = $response->json();
        
        // SakuRupiah returns data as array, extract first element
        if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
          $transactionData = $result['data'][0];
        } else {
          $transactionData = $result;
        }
        
        return [
          'status' => true,
          'data' => $transactionData,
          'gateway' => 'sakurupiah'
        ];
      } else {
        return [
          'status' => false,
          'data' => $response->json(),
          'message' => 'SakuRupiah Transaction Error: ' . $response->body(),
          'gateway' => 'sakurupiah'
        ];
      }
    } catch (\Exception $e) {
      return [
        'status' => false,
        'data' => [],
        'message' => 'SakuRupiah Transaction Error: ' . $e->getMessage(),
        'gateway' => 'sakurupiah'
      ];
    }
  }

  public function checkTransactionDetail(string $reference): array
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key'),
      ])->post($this->baseUrl . '/check-transaction.php', [
        'api_id' => $this->getConfig('api_id'),
        'merchant_ref' => $reference
      ]);

      if ($response->successful()) {
        $result = $response->json();
        
        // SakuRupiah returns data as array, extract first element
        if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
          $transactionData = $result['data'][0];
        } else {
          $transactionData = $result;
        }
        
        return [
          'status' => true,
          'data' => $transactionData,
          'gateway' => 'sakurupiah'
        ];
      } else {
        return [
          'status' => false,
          'data' => $response->json(),
          'message' => 'SakuRupiah Transaction Detail Error: ' . $response->body(),
          'gateway' => 'sakurupiah'
        ];
      }
    } catch (\Exception $e) {
      return [
        'status' => false,
        'data' => [],
        'message' => 'SakuRupiah Transaction Detail Error: ' . $e->getMessage(),
        'gateway' => 'sakurupiah'
      ];
    }
  }

  /**
   * Generate signature for API requests
   * Formula: hash_hmac('sha256', api_id.method.merchant_ref.amount.apikey, apikey)
   *
   * @param string $apiId
   * @param string $method
   * @param string $merchantRef
   * @param int $amount
   * @param string $apiKey
   * @return string
   */
  protected function generateSignature(string $apiId, string $method, string $merchantRef, int $amount, string $apiKey): string
  {
    $signatureString = $apiId . $method . $merchantRef . $amount;
    return hash_hmac('sha256', $signatureString, $apiKey);
  }

  /**
   * Verify webhook signature
   *
   * @param array $payload
   * @param string|null $signature
   * @return bool
   */
  public function verifyWebhookSignature(array $payload, ?string $signature = null): bool
  {
    // Validate required webhook fields
    $requiredFields = ['api_id', 'merchant_ref', 'status'];

    foreach ($requiredFields as $field) {
      if (!isset($payload[$field])) {
        \Log::warning('SakuRupiah webhook missing required field: ' . $field);
        return false;
      }
    }

    // Verify api_id matches configured api_id
    if ($payload['api_id'] !== $this->getConfig('api_id')) {
      \Log::warning('SakuRupiah webhook api_id mismatch');
      return false;
    }

    // Verify signature if provided
    if ($signature && isset($payload['method']) && isset($payload['amount'])) {
      $expectedSignature = $this->generateSignature(
        $payload['api_id'],
        $payload['method'],
        $payload['merchant_ref'],
        $payload['amount'],
        $this->getConfig('api_key')
      );

      if (!hash_equals($expectedSignature, $signature)) {
        \Log::warning('SakuRupiah webhook signature mismatch');
        return false;
      }
    }

    return true;
  }
}
