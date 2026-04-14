<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $gateways = [
      [
        'name' => 'tripay',
        'display_name' => 'Tripay',
        'status' => 'inactive',
        'description' => 'Tripay Payment Gateway - Support berbagai metode pembayaran di Indonesia',
        'data' => [
          'api_key' => '',
          'private_key' => '',
          'merchant_code' => '',
          'environment' => 'sandbox', // sandbox | production
        ],
      ],
      [
        'name' => 'xendit',
        'display_name' => 'Xendit',
        'status' => 'inactive',
        'description' => 'Xendit Payment Gateway - Platform pembayaran digital Indonesia',
        'data' => [
          'api_key' => '',
          'callback_token' => '',
          'environment' => 'sandbox', // sandbox | production
        ],
      ],
      [
        'name' => 'sakurupiah',
        'display_name' => 'SakuRupiah',
        'status' => 'inactive',
        'description' => 'SakuRupiah Payment Gateway - PPOB dan Payment Gateway',
        'data' => [
          'api_key' => '',
          'secret_key' => '',
          'merchant_id' => '',
          'environment' => 'sandbox', // sandbox | production
        ],
      ],
      [
        'name' => 'paymenku',
        'display_name' => 'Paymenku',
        'status' => 'inactive',
        'description' => 'Paymenku Payment Gateway - Solusi payment gateway terpercaya',
        'data' => [
          'api_key' => '',
          'secret_key' => '',
          'merchant_code' => '',
          'environment' => 'sandbox', // sandbox | production
        ],
      ],
    ];

    foreach ($gateways as $gateway) {
      PaymentGateway::updateOrCreate(
        ['name' => $gateway['name']],
        $gateway
      );
    }
  }
}
