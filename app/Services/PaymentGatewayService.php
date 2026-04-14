<?php

namespace App\Services;

use App\Models\PaymentGateway as PaymentGatewayModel;
use App\Services\Gateways\TripayGateway;
use App\Services\Gateways\XenditGateway;
use App\Services\Gateways\SakurupiahGateway;
use App\Services\Gateways\PaymenkuGateway;
use Exception;

class PaymentGatewayService
{
  /**
   * Get active payment gateway instance
   *
   * @param string|null $gatewayName Specific gateway name or null for default active
   * @return mixed
   * @throws Exception
   */
  public static function gateway(?string $gatewayName = null)
  {
    // If no gateway specified, get the first active one
    if (!$gatewayName) {
      $gateway = PaymentGatewayModel::active()->first();

      if (!$gateway) {
        throw new Exception('No active payment gateway found');
      }

      $gatewayName = $gateway->name;
    } else {
      $gateway = PaymentGatewayModel::where('name', $gatewayName)->first();

      if (!$gateway) {
        throw new Exception("Payment gateway '{$gatewayName}' not found");
      }

      if (!$gateway->isActive()) {
        throw new Exception("Payment gateway '{$gatewayName}' is not active");
      }
    }

    return self::createGatewayInstance($gateway);
  }

  /**
   * Get all active payment gateways
   *
   * @return array
   */
  public static function getActiveGateways(): array
  {
    return PaymentGatewayModel::active()->get()->map(function ($gateway) {
      return [
        'name' => $gateway->name,
        'display_name' => $gateway->display_name,
        'description' => $gateway->description,
      ];
    })->toArray();
  }

  /**
   * Check if a specific gateway is active
   *
   * @param string $gatewayName
   * @return bool
   */
  public static function isGatewayActive(string $gatewayName): bool
  {
    return PaymentGatewayModel::where('name', $gatewayName)
      ->where('status', 'active')
      ->exists();
  }

  /**
   * Create gateway instance based on gateway name
   *
   * @param PaymentGatewayModel $gateway
   * @return mixed
   * @throws Exception
   */
  private static function createGatewayInstance(PaymentGatewayModel $gateway)
  {
    switch ($gateway->name) {
      case 'tripay':
        return new TripayGateway($gateway);

      case 'xendit':
        return new XenditGateway($gateway);

      case 'sakurupiah':
        return new SakurupiahGateway($gateway);

      case 'paymenku':
        return new PaymenkuGateway($gateway);

      default:
        throw new Exception("Gateway '{$gateway->name}' is not supported");
    }
  }

  /**
   * Get payment channels for a specific gateway
   *
   * @param string|null $gatewayName
   * @return array|null
   */
  public static function getPaymentChannels(?string $gatewayName = null): ?array
  {
    try {
      $gateway = self::gateway($gatewayName);
      return $gateway->getPaymentChannels();
    } catch (Exception $e) {
      \Log::error('Payment Gateway Error: ' . $e->getMessage());
      return null;
    }
  }

  /**
   * Create transaction across any gateway
   *
   * @param array $data
   * @param string|null $gatewayName
   * @return array
   */
  public static function createTransaction(array $data, ?string $gatewayName = null): array
  {
    try {
      $gateway = self::gateway($gatewayName);
      return $gateway->createTransaction($data);
    } catch (Exception $e) {
      \Log::error('Payment Gateway Transaction Error: ' . $e->getMessage());
      return [
        'status' => false,
        'message' => $e->getMessage(),
        'data' => []
      ];
    }
  }

  /**
   * Check transaction status
   *
   * @param string $reference Transaction reference
   * @param string $gatewayName Gateway name
   * @return array
   */
  public static function checkTransactionStatus(string $reference, string $gatewayName): array
  {
    try {
      $gateway = self::gateway($gatewayName);
      return $gateway->checkTransactionDetail($reference);
    } catch (Exception $e) {
      \Log::error('Payment Gateway Check Status Error: ' . $e->getMessage());
      return [
        'status' => false,
        'message' => $e->getMessage(),
        'data' => []
      ];
    }
  }

  /**
   * Calculate fee for transaction
   *
   * @param string $paymentMethod
   * @param int $amount
   * @param string|null $gatewayName
   * @return array|null
   */
  public static function calculateFee(string $paymentMethod, int $amount, ?string $gatewayName = null): ?array
  {
    try {
      $gateway = self::gateway($gatewayName);
      return $gateway->calculateFee($paymentMethod, $amount);
    } catch (Exception $e) {
      \Log::error('Payment Gateway Fee Calculation Error: ' . $e->getMessage());
      return null;
    }
  }
}
