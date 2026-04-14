<?php

namespace App\Services\Gateways;

use App\Models\PaymentGateway;

abstract class BaseGateway
{
  public $gateway;
  protected $config;
  protected $baseUrl;

  public function __construct(PaymentGateway $gateway)
  {
    $this->gateway = $gateway;
    $this->config = $gateway->data ?? [];
    $this->setBaseUrl();
  }

  /**
   * Set base URL based on environment
   */
  abstract protected function setBaseUrl(): void;

  /**
   * Get payment channels/methods available
   *
   * @return array|null
   */
  abstract public function getPaymentChannels(): ?array;

  /**
   * Create new payment transaction
   *
   * @param array $data
   * @return array
   */
  abstract public function createTransaction(array $data): array;

  /**
   * Check transaction detail/status
   *
   * @param string $reference
   * @return array
   */
  abstract public function checkTransactionDetail(string $reference): array;

  /**
   * Calculate transaction fee
   *
   * @param string $paymentMethod
   * @param int $amount
   * @return array|null
   */
  abstract public function calculateFee(string $paymentMethod, int $amount): ?array;

  /**
   * Get configuration value
   *
   * @param string $key
   * @param mixed $default
   * @return mixed
   */
  protected function getConfig(string $key, $default = null)
  {
    return $this->config[$key] ?? $default;
  }

  /**
   * Check if in production mode
   *
   * @return bool
   */
  protected function isProduction(): bool
  {
    return $this->getConfig('environment', 'sandbox') === 'production';
  }
}
