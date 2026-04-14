<?php

namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;

class TripayGateway extends BaseGateway
{
  protected function setBaseUrl(): void
  {
    $this->baseUrl = $this->isProduction()
      ? 'https://tripay.co.id/api/'
      : 'https://tripay.co.id/api-sandbox/';
  }

  public function getPaymentChannels(): ?array
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key')
      ])->get($this->baseUrl . 'merchant/payment-channel');

      if ($response->successful()) {
        return $response->json();
      }

      return null;
    } catch (\Exception $e) {
      \Log::error('Tripay Payment Channels Error: ' . $e->getMessage());
      return null;
    }
  }

  public function getPaymentInstructions(string $code): ?array
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key')
      ])->get($this->baseUrl . 'payment/instruction', [
        'code' => $code
      ]);

      if ($response->successful()) {
        return $response->json();
      }

      return null;
    } catch (\Exception $e) {
      \Log::error('Tripay Payment Instructions Error: ' . $e->getMessage());
      return null;
    }
  }

  public function calculateFee(string $paymentMethod, int $amount): ?array
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key')
      ])->get($this->baseUrl . 'merchant/fee-calculator', [
        'code' => $paymentMethod,
        'amount' => $amount
      ]);

      if ($response->successful()) {
        return $response->json();
      }

      return null;
    } catch (\Exception $e) {
      \Log::error('Tripay Fee Calculation Error: ' . $e->getMessage());
      return null;
    }
  }

  public function createTransaction(array $data): array
  {
    try {
      $signature = $this->generateSignature(
        $data['amount'],
        $data['merchant_ref']
      );

      $payload = [
        'method' => $data['method'],
        'merchant_ref' => $data['merchant_ref'],
        'amount' => $data['amount'],
        'customer_name' => $data['customer_name'],
        'customer_email' => $data['customer_email'],
        'customer_phone' => $data['customer_phone'],
        'order_items' => $data['order_items'],
        'return_url' => $data['return_url'] ?? url('/'),
        'expired_time' => $data['expired_time'] ?? (time() + (24 * 60 * 60)),
        'signature' => $signature
      ];

      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key')
      ])->post($this->baseUrl . 'transaction/create', $payload);

      if ($response->successful()) {
        return [
          'status' => true,
          'data' => $response->json(),
          'gateway' => 'tripay'
        ];
      } else {
        return [
          'status' => false,
          'data' => $response->json(),
          'message' => 'Tripay Transaction Error: ' . $response->body(),
          'gateway' => 'tripay'
        ];
      }
    } catch (\Exception $e) {
      return [
        'status' => false,
        'data' => [],
        'message' => 'Tripay Transaction Error: ' . $e->getMessage(),
        'gateway' => 'tripay'
      ];
    }
  }

  public function checkTransactionDetail(string $reference): array
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key')
      ])->get($this->baseUrl . 'transaction/detail', [
        'reference' => $reference
      ]);

      if ($response->successful()) {
        return [
          'status' => true,
          'data' => $response->json(),
          'gateway' => 'tripay'
        ];
      } else {
        return [
          'status' => false,
          'data' => $response->json(),
          'message' => 'Tripay Transaction Detail Error: ' . $response->body(),
          'gateway' => 'tripay'
        ];
      }
    } catch (\Exception $e) {
      return [
        'status' => false,
        'data' => [],
        'message' => 'Tripay Transaction Detail Error: ' . $e->getMessage(),
        'gateway' => 'tripay'
      ];
    }
  }

  /**
   * Generate signature for transaction
   *
   * @param int $amount
   * @param string $merchantRef
   * @return string
   */
  public function generateSignature(int $amount, string $merchantRef): string
  {
    $merchantCode = $this->getConfig('merchant_code');
    $privateKey = $this->getConfig('private_key');

    $signature = $merchantCode . $merchantRef . $amount;
    return hash_hmac('sha256', $signature, $privateKey);
  }

  /**
   * Get merchant code
   *
   * @return string|null
   */
  public function getMerchantCode(): ?string
  {
    return $this->getConfig('merchant_code');
  }

  /**
   * Get private key
   *
   * @return string|null
   */
  public function getPrivateKey(): ?string
  {
    return $this->getConfig('private_key');
  }
}
