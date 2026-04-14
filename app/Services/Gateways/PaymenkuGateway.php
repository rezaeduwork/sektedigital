<?php

namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;

class PaymenkuGateway extends BaseGateway
{
  protected function setBaseUrl(): void
  {
    $this->baseUrl = $this->isProduction()
      ? 'https://paymenku.com/api/v1'
      : 'https://paymenku.com/api/v1';
  }

  public function getPaymentChannels(): ?array
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key'),
        'Content-Type' => 'application/json'
      ])->get($this->baseUrl . '/payment-methods');

      if ($response->successful()) {
        $responseData = $response->json();
        return $responseData['data'] ?? $responseData;
      }

      return null;
    } catch (\Exception $e) {
      \Log::error('Paymenku Payment Channels Error: ' . $e->getMessage());
      return null;
    }
  }

  /**
   * Parse payment channels response to flat array
   *
   * @param array $channels
   * @return array
   */
  public function parsePaymentChannels(array $channels): array
  {
    $flatChannels = [];

    foreach ($channels as $type => $typeChannels) {
      foreach ($typeChannels as $channel) {
        $flatChannels[] = [
          'code' => $channel['code'] ?? '',
          'name' => $channel['name'] ?? '',
          'type' => $channel['type'] ?? '',
          'type_label' => $channel['type_label'] ?? '',
          'icon' => $channel['icon'] ?? '',
          'description' => $channel['description'] ?? '',
          'fee' => [
            'flat' => $channel['fee']['flat'] ?? 0,
            'percent' => $channel['fee']['percent'] ?? 0,
            'display' => $channel['fee']['display'] ?? '',
          ],
          'gateway' => 'paymenku',
        ];
      }
    }

    return $flatChannels;
  }

  /**
   * Get payment channels grouped by type
   *
   * @return array|null
   */
  public function getPaymentChannelsGrouped(): ?array
  {
    $channels = $this->getPaymentChannels();

    if ($channels === null) {
      return null;
    }

    return $channels;
  }

  /**
   * Get payment channels as flat array
   *
   * @return array|null
   */
  public function getPaymentChannelsFlat(): ?array
  {
    $channels = $this->getPaymentChannels();

    if ($channels === null) {
      return null;
    }

    return $this->parsePaymentChannels($channels);
  }

  public function calculateFee(string $paymentMethod, int $amount): ?array
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key'),
        'Content-Type' => 'application/json'
      ])->post($this->baseUrl . '/fee/calculate', [
        'channel_code' => $paymentMethod,
        'amount' => $amount
      ]);

      if ($response->successful()) {
        return $response->json();
      }

      return null;
    } catch (\Exception $e) {
      \Log::error('Paymenku Fee Calculation Error: ' . $e->getMessage());
      return null;
    }
  }

  public function createTransaction(array $data): array
  {
    try {
      $payload = [
        'reference_id' => $data['merchant_ref'],
        'amount' => $data['amount'],
        'customer_name' => $data['customer_name'],
        'customer_email' => $data['customer_email'],
        'customer_phone' => $data['customer_phone'] ?? '',
        'channel_code' => $data['method'],
        'return_url' => $data['return_url'] ?? url('/'),
      ];

      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key'),
        'Content-Type' => 'application/json'
      ])->post($this->baseUrl . '/transaction/create', $payload);

      if ($response->successful()) {
        return [
          'status' => true,
          'data' => $response->json(),
          'gateway' => 'paymenku'
        ];
      } else {
        return [
          'status' => false,
          'data' => $response->json(),
          'message' => 'Paymenku Transaction Error: ' . $response->body(),
          'gateway' => 'paymenku'
        ];
      }
    } catch (\Exception $e) {
      return [
        'status' => false,
        'data' => [],
        'message' => 'Paymenku Transaction Error: ' . $e->getMessage(),
        'gateway' => 'paymenku'
      ];
    }
  }

  public function checkTransactionDetail(string $reference): array
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->getConfig('api_key'),
        'Content-Type' => 'application/json'
      ])->get($this->baseUrl . '/transaction/' . $reference);

      if ($response->successful()) {
        $responseData = $response->json();
        return [
          'status' => true,
          'data' => $responseData['data'] ?? $responseData,
          'gateway' => 'paymenku'
        ];
      } else {
        return [
          'status' => false,
          'data' => $response->json(),
          'message' => 'Paymenku Transaction Detail Error: ' . $response->body(),
          'gateway' => 'paymenku'
        ];
      }
    } catch (\Exception $e) {
      return [
        'status' => false,
        'data' => [],
        'message' => 'Paymenku Transaction Detail Error: ' . $e->getMessage(),
        'gateway' => 'paymenku'
      ];
    }
  }

  /**
   * Parse transaction detail response to standardized format
   *
   * @param array $data
   * @return array
   */
  public function parseTransactionDetail(array $data): array
  {
    return [
      'transaction_id' => $data['trx_id'] ?? '',
      'reference_id' => $data['reference_id'] ?? '',
      'amount' => $data['amount'] ?? 0,
      'fee' => $data['total_fee'] ?? 0,
      'amount_received' => $data['amount_received'] ?? 0,
      'status' => $data['status'] ?? '',
      'is_sandbox' => $data['is_sandbox'] ?? false,
      'customer_name' => $data['customer_name'] ?? '',
      'customer_email' => $data['customer_email'] ?? '',
      'payment_channel' => [
        'code' => $data['payment_channel']['code'] ?? '',
        'name' => $data['payment_channel']['name'] ?? '',
        'type' => $data['payment_channel']['type'] ?? '',
      ],
      'pay_url' => $data['pay_url'] ?? '',
      'paid_at' => $data['paid_at'] ?? null,
      'created_at' => $data['created_at'] ?? null,
      'updated_at' => $data['updated_at'] ?? null,
      'gateway' => 'paymenku',
    ];
  }

  /**
   * Verify webhook signature
   *
   * Note: Implement proper signature verification based on Paymenku's documentation
   * if they provide signature verification mechanism
   *
   * @param array $payload
   * @param string|null $receivedSignature
   * @return bool
   */
  public function verifyWebhookSignature(array $payload, ?string $receivedSignature = null): bool
  {
    // Validate required webhook fields
    $requiredFields = ['event', 'trx_id', 'reference_id', 'status', 'amount'];

    foreach ($requiredFields as $field) {
      if (!isset($payload[$field])) {
        \Log::warning('Paymenku webhook missing required field: ' . $field);
        return false;
      }
    }

    // TODO: Implement signature verification if Paymenku provides it
    // For now, we validate the structure only
    return true;
  }

  /**
   * Parse webhook payload to standardized format
   *
   * @param array $payload
   * @return array
   */
  public function parseWebhookPayload(array $payload): array
  {
    return [
      'event' => $payload['event'] ?? '',
      'transaction_id' => $payload['trx_id'] ?? '',
      'reference_id' => $payload['reference_id'] ?? '',
      'status' => $payload['status'] ?? '',
      'amount' => $payload['amount'] ?? 0,
      'fee' => $payload['amount_fee'] ?? 0,
      'amount_received' => $payload['amount_received'] ?? 0,
      'channel' => $payload['payment_channel'] ?? '',
      'customer_name' => $payload['customer_name'] ?? '',
      'customer_email' => $payload['customer_email'] ?? '',
      'paid_at' => $payload['paid_at'] ?? null,
      'created_at' => $payload['created_at'] ?? null,
      'gateway' => 'paymenku',
    ];
  }
}
