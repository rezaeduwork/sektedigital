<?php

namespace App\Services\Gateways;

use App\Models\PaymentGateway;

class GatewayFactory
{
  /**
   * Create a gateway instance based on gateway name
   *
   * @param PaymentGateway $gateway
   * @return BaseGateway|null
   */
  public static function create(PaymentGateway $gateway): ?BaseGateway
  {
    $gatewayMap = [
      'tripay' => TripayGateway::class,
      'xendit' => XenditGateway::class,
      'sakurupiah' => SakurupiahGateway::class,
      'paymenku' => PaymenkuGateway::class,
    ];

    $gatewayClass = $gatewayMap[$gateway->name] ?? null;

    if (!$gatewayClass || !class_exists($gatewayClass)) {
      \Log::warning("Gateway class not found for: {$gateway->name}");
      return null;
    }

    return new $gatewayClass($gateway);
  }

  /**
   * Get all active gateways
   *
   * @return array
   */
  public static function getActiveGateways(): array
  {
    $gateways = PaymentGateway::active()->get();
    $instances = [];

    foreach ($gateways as $gateway) {
      $instance = self::create($gateway);
      if ($instance) {
        $instances[] = [
          'gateway' => $gateway,
          'instance' => $instance,
        ];
      }
    }

    return $instances;
  }

  /**
   * Get payment methods from all active gateways
   *
   * @return array
   */
  public static function getAllPaymentMethods(): array
  {
    $activeGateways = self::getActiveGateways();
    $allMethods = [];
    foreach ($activeGateways as $gatewayData) {
      $gateway = $gatewayData['gateway'];
      $instance = $gatewayData['instance'];

      try {
        $channels = $instance->getPaymentChannels();
        if ($channels) {
          // Handle different response formats
          $methods = is_array($channels) ? $channels : [];

          // If it's grouped by type (like Paymenku), flatten it
          if (self::isGroupedChannels($methods)) {
            $methods = self::flattenGroupedChannels($methods);
          }
          // Add gateway information to each method
          foreach ($methods as &$method) {
            $method['gateway_id'] = $gateway->id;
            $method['gateway_name'] = $gateway->name;
            $method['gateway_display_name'] = $gateway->display_name;
          }

          $allMethods = array_merge($allMethods, $methods);
        }
      } catch (\Exception $e) {
        \Log::error("Failed to fetch payment methods from {$gateway->name}: " . $e->getMessage());
      }
    }

    return $allMethods;
  }

  /**
   * Check if channels are grouped by type
   *
   * @param array $channels
   * @return bool
   */
  private static function isGroupedChannels(array $channels): bool
  {
    if (empty($channels)) {
      return false;
    }

    // Check if first level keys are types (va, ewallet, qris, etc.)
    foreach ($channels as $key => $value) {
      if (is_string($key) && is_array($value) && !isset($value['code'])) {
        return true;
      }
    }

    return false;
  }

  /**
   * Flatten grouped channels into a single array
   *
   * @param array $groupedChannels
   * @return array
   */
  private static function flattenGroupedChannels(array $groupedChannels): array
  {
    $flatChannels = [];

    foreach ($groupedChannels as $type => $channels) {
      if (is_array($channels)) {
        foreach ($channels as $channel) {
          $flatChannels[] = $channel;
        }
      }
    }

    return $flatChannels;
  }

  /**
   * Find gateway instance by gateway ID
   *
   * @param int $gatewayId
   * @return BaseGateway|null
   */
  public static function findByGatewayId(int $gatewayId): ?BaseGateway
  {
    $gateway = PaymentGateway::find($gatewayId);

    if (!$gateway || !$gateway->isActive()) {
      return null;
    }

    return self::create($gateway);
  }

  /**
   * Find gateway instance by gateway name
   *
   * @param string $gatewayName
   * @return BaseGateway|null
   */
  public static function findByGatewayName(string $gatewayName): ?BaseGateway
  {
    $gateway = PaymentGateway::where('name', $gatewayName)
      ->where('status', 'active')
      ->first();

    if (!$gateway) {
      return null;
    }

    return self::create($gateway);
  }
}
