<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Tripay
{
  private $apiKey;
  private $merchantCode;
  private $secretKey;
  private $baseUrl = 'https://tripay.co.id/api-sandbox/';

  public function __construct()
  {
    $this->apiKey = config('services.tripay.key');
    $this->secretKey = config('services.tripay.secret');
    $this->merchantCode = config('services.tripay.merchant');
  }

  /**
   * Get available payment channels
   *
   * @return array|null
   */
  public function getPaymentChannels()
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey
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

  /**
   * Get payment instructions for a specific payment method
   *
   * @param string $code Payment method code (e.g., 'BRIVA')
   * @return array|null
   */
  public function getPaymentInstructions(string $code)
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey
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

  /**
   * Calculate transaction fee for specific payment method
   *
   * @param string $paymentMethod Payment method code
   * @param int $amount Transaction amount
   * @return array|null
   */
  public function calculateFee(string $paymentMethod, int $amount)
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey
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

  /**
   * Create new payment transaction
   *
   * @param array $data Transaction data
   * @return array|null
   */
  public function createTransaction(array $data)
  {
    try {
      // Generate signature
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
        'return_url' => $data['return_url'],
        'expired_time' => $data['expired_time'] ?? (time() + (24 * 60 * 60)), // Default 24 hours
        'signature' => $signature
      ];

      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey
      ])->post($this->baseUrl . 'transaction/create', $payload);

      if ($response->successful()) {
        return $response->json();
      }

      return null;
    } catch (\Exception $e) {
      \Log::error('Tripay Transaction Creation Error: ' . $e->getMessage());
      return null;
    }
  }

  /**
   * Generate signature for transaction
   *
   * @param int $amount Transaction amount
   * @param string $merchantRef Merchant reference
   * @return string
   */
  private function generateSignature(int $amount, string $merchantRef): string
  {
    $signature = $this->merchantCode . $merchantRef . $amount;
    return hash_hmac('sha256', $signature, $this->secretKey);
  }
}
