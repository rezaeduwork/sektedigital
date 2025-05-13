<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedWhatsappBotCommands extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'whatsapp:seed-bot-commands {--store_id= : Optional store ID to seed commands for a specific store}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Seed default WhatsApp bot commands for stores with WhatsApp connections';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $storeId = $this->option('store_id');

    if ($storeId) {
      // Seed commands for a specific store
      $store = \App\Models\Store::find($storeId);

      if (!$store) {
        $this->error("Store with ID {$storeId} not found.");
        return 1;
      }

      if (!$store->whatsapp) {
        $this->error("Store with ID {$storeId} does not have WhatsApp connection.");
        return 1;
      }

      $this->info("Seeding WhatsApp bot commands for store: {$store->name}");

      // Create default commands for this store
      $this->createDefaultCommandsForStore($store);

      $this->info("WhatsApp bot commands seeded successfully for store: {$store->name}");
    } else {
      // Seed commands for all stores with WhatsApp connections
      $stores = \App\Models\Store::whereHas('whatsapp')->get();

      if ($stores->isEmpty()) {
        $this->error("No stores with WhatsApp connections found.");
        return 1;
      }

      $this->info("Seeding WhatsApp bot commands for {$stores->count()} stores...");

      foreach ($stores as $store) {
        $this->createDefaultCommandsForStore($store);
      }

      $this->info("WhatsApp bot commands seeded successfully for all stores.");
    }

    return 0;
  }

  /**
   * Create default commands for a store
   *
   * @param \App\Models\Store $store
   * @return void
   */
  protected function createDefaultCommandsForStore($store)
  {
    // Default commands for the store
    $defaultCommands = [
      [
        'command' => 'help',
        'description' => 'Show available commands',
        'response_template' => "📱 *Available Commands for {{store_name}}* 📱\n\n" .
          "*/products* - Browse all products\n" .
          "*/cart* - View your shopping cart\n" .
          "*/checkout* - Proceed to checkout\n" .
          "*/cancel* - Cancel current action\n" .
          "*/help* - Show this help message\n\n" .
          "You can also simply type what you're looking for to search our products!",
        'is_active' => true,
        'display_order' => 1,
        'parameters' => null
      ],
      [
        'command' => 'products',
        'description' => 'List all products',
        'response_template' => "🛍️ *Products from {{store_name}}* 🛍️\n\n" .
          "Here are our available products:\n\n" .
          "[Products will be dynamically inserted here]\n\n" .
          "To view details about a specific product, type */view product_number*",
        'is_active' => true,
        'display_order' => 2,
        'parameters' => null
      ],
      [
        'command' => 'cart',
        'description' => 'View shopping cart',
        'response_template' => "🛒 *Your Shopping Cart* 🛒\n\n" .
          "Here are the items in your cart:\n\n" .
          "[Cart items will be inserted here]\n\n" .
          "To checkout, type */checkout*\n" .
          "To remove an item, type */remove item_number*",
        'is_active' => true,
        'display_order' => 3,
        'parameters' => null
      ],
      [
        'command' => 'checkout',
        'description' => 'Proceed to checkout',
        'response_template' => "💳 *Checkout Process* 💳\n\n" .
          "Thank you for shopping with {{store_name}}!\n\n" .
          "To complete your purchase, please follow these steps:\n\n" .
          "1. Confirm your order\n" .
          "2. Provide delivery information\n" .
          "3. Select payment method\n\n" .
          "To begin, type */confirm* to confirm your order.",
        'is_active' => true,
        'display_order' => 4,
        'parameters' => null
      ],
      [
        'command' => 'about',
        'description' => 'About our store',
        'response_template' => "ℹ️ *About {{store_name}}* ℹ️\n\n" .
          "Welcome to {{store_name}}!\n\n" .
          "We offer a wide range of products at competitive prices. Our store is committed to providing excellent customer service and high-quality products.\n\n" .
          "Thank you for choosing us for your shopping needs!",
        'is_active' => true,
        'display_order' => 5,
        'parameters' => null
      ],
      [
        'command' => 'contact',
        'description' => 'Contact information',
        'response_template' => "📞 *Contact {{store_name}}* 📞\n\n" .
          "For any inquiries or assistance, please contact us:\n\n" .
          "Email: [Store email will be inserted here]\n" .
          "Phone: [Store phone will be inserted here]\n\n" .
          "We're happy to help!",
        'is_active' => true,
        'display_order' => 6,
        'parameters' => null
      ],
    ];

    // Insert default commands for this store
    foreach ($defaultCommands as $command) {
      \App\Models\WhatsappBotCommand::updateOrCreate(
        [
          'store_id' => $store->id,
          'command' => $command['command']
        ],
        [
          'description' => $command['description'],
          'response_template' => $command['response_template'],
          'is_active' => $command['is_active'],
          'display_order' => $command['display_order'],
          'parameters' => $command['parameters']
        ]
      );
    }

    $this->info("Default WhatsApp bot commands created for store: {$store->name}");
  }
}
