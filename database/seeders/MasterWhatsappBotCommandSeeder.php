<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WhatsappBotCommandCategory;
use App\Models\WhatsappBotCommand;

class MasterWhatsappBotCommandSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Delete existing master commands
    WhatsappBotCommand::query()->delete();
    WhatsappBotCommandCategory::query()->delete();
    $this->bitneetPpob();
  }
  public function bitneetMarketplace()
  {
    // Master category
    $categoryData = [
      'name' => 'Bitnet Marketplace',
      'description' => 'Bot for marketplace integration',
      'is_master' => true,
      'display_order' => 1,
      'is_active' => true
    ];
    $category = WhatsappBotCommandCategory::updateOrCreate(
      [
        'name' => $categoryData['name'],
        'is_master' => true
      ],
      $categoryData
    );
    // Perintah Master
    $masterCommands = [
      [
        'command' => 'help',
        'name' => 'Bantuan',
        'description' => 'Tampilkan semua perintah yang tersedia',
        'response_template' =>
        "🏪 {Store.name}\n" .
          "꘎━━━━━━━━━━━━━━━━━━━━━━━━━꘎\n" .
          "Profile {Store.website}\n" .
          "{Store.time}\n" .
          "꘎━━━━━━━━━━━━━━━━━━━━━━━━━꘎\n\n" .
          "⚜ *Perintah yang Tersedia* ⚜\n" .
          "⤷ *products* - Lihat semua produk\n" .
          "⤷ *search [kata_kunci]* - Cari produk\n" .
          "⤷ *show [id]* - Tampilkan produk berdasarkan ID",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 1,
        'parameters' => [],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => null
      ],
      [
        'command' => 'search',
        'name' => 'Cari Produk',
        'description' => 'Cari produk berdasarkan kata kunci',
        'response_template' => "🔍 *Hasil Pencarian untuk '{Product.input.query}'*\n\n" .
          "{Product.search}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [
          'query' => 'string'
        ],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => null
      ],
      [
        'command' => 'products',
        'name' => 'Produk',
        'description' => 'Daftar semua produk',
        'response_template' => "📋 *Produk Tersedia*\n\n" .
          "{Product.all}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 2,
        'parameters' => [],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => null
      ],
      [
        'command' => 'show',
        'name' => 'Tampilkan Produk',
        'description' => 'Tampilkan produk berdasarkan ID',
        'response_template' => "Produk {Product.input.id}\n\n" .
          "{Product.find}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [
          'id' => 'number'
        ],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => null
      ],
    ];

    // Insert or update master commands
    foreach ($masterCommands as $commandData) {
      WhatsappBotCommand::updateOrCreate(
        [
          'command' => $commandData['command'],
          'is_master' => true
        ],
        $commandData
      );
    }

    // append to store
    $stores = \App\Models\Store::all();
    foreach ($stores as $store) {
      // Copy master commands to the store
      foreach ($masterCommands as $commandData) {
        WhatsappBotCommand::create(
          array_merge($commandData, [
            'category_id' => $category->id,
            'command' => $commandData['command'],
            'store_id' => $store->id,
            'is_master' => false
          ])
        );
      }
    }
  }
  public function bitneetPpob()
  {
    // Master category
    $categoryData = [
      'name' => 'Bitnet PPOB',
      'description' => 'Bot untuk layanan PPOB',
      'is_master' => true,
      'display_order' => 2,
      'is_active' => true
    ];
    $category = WhatsappBotCommandCategory::updateOrCreate(
      [
        'name' => $categoryData['name'],
        'is_master' => true
      ],
      $categoryData
    );

    // Perintah Master PPOB
    $masterCommands = [
      // help
      [
        'command' => 'help',
        'name' => 'PPOB',
        'description' => 'Layanan PPOB',
        'response_template' => "{Ppob.help}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 1,
        'parameters' => [],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => null
      ],
      // game
      [
        'command' => 'game',
        'name' => 'Top Up Game',
        'description' => 'Layanan Top Up Game',
        'response_template' => "{Ppob.gameBrandList}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => 'ppob.select_menu'
      ],
      // pulsa
      [
        'command' => 'pulsa',
        'name' => 'Top Up Pulsa',
        'description' => 'Layanan Top Up Pulsa',
        'response_template' => "📱 *Top Up Pulsa*\n\n" .
          "Silakan pilih nominal pulsa yang ingin di-top up:\n" .
          "{Ppob.pulsaBrandaList}" .
          "Ketik *select [number]* untuk melanjutkan.",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => 'select_menu'
      ],
      // data
      [
        'command' => 'data',
        'name' => 'Beli Paket Data',
        'description' => 'Layanan Beli Paket Data',
        'response_template' => "📶 *Beli Paket Data*\n\n" .
          "Silakan pilih paket data yang ingin dibeli:\n" .
          "{Ppob.dataBrandList}" .
          "Ketik *select [number]* untuk melanjutkan.",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => 'select_menu'
      ],
      // select brand
      [
        'command' => 'select',
        'name' => 'Pilih produk',
        'description' => 'Pilih produk untuk melanjutkan',
        'response_template' => "{Ppob.productList}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [
          'index' => 'number'
        ],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => 'ppob.select_brand'
      ],
      // select product
      [
        'command' => 'p',
        'name' => 'Select produk',
        'description' => 'Select produk',
        'response_template' => "{Ppob.productDetail}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [
          'code' => 'string'
        ],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => 'ppob.select_product'
      ],
      // product detail
      [
        'command' => 'pay',
        'name' => 'Pembayaran',
        'description' => 'Layanan Pembayaran',
        'response_template' =>  "{Ppob.paymentChannels}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => 'ppob.payment'
      ],
      // checkout
      [
        'command' => 'checkout',
        'name' => 'Checkout',
        'description' => 'Konfirmasi pembayaran',
        'response_template' => "{Ppob.processPayment}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [
          'method' => 'string'
        ],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => 'ppob.payment'
      ],
      // status
      [
        'command' => 'status',
        'name' => 'Status Transaksi Terakhir',
        'description' => 'Cek status transaksi terakhir',
        'response_template' => "{Ppob.transactionStatus}",
        'is_active' => true,
        'is_master' => true,
        'display_order' => 9,
        'parameters' => [],
        'category_id' => $category->id,
        'handler_class' => null,
        'state' => null
      ],
    ];

    // Insert or update master commands
    foreach ($masterCommands as $commandData) {
      WhatsappBotCommand::updateOrCreate(
        [
          'command' => $commandData['command'],
          'is_master' => true
        ],
        $commandData
      );
    }

    // append to store
    $stores = \App\Models\Store::all();
    foreach ($stores as $store) {
      // Copy master commands to the store
      foreach ($masterCommands as $commandData) {
        WhatsappBotCommand::create(
          array_merge($commandData, [
            'category_id' => $category->id,
            'command' => $commandData['command'],
            'store_id' => $store->id,
            'is_master' => false
          ])
        );
      }
    }
  }
}
