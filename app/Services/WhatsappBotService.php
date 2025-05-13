<?php

namespace App\Services;

use App\Models\Store;
use App\Models\Product;
use App\Models\WhatsappBotMessage;
use App\Models\WhatsappBotCommand;
use App\Models\WhatsappCustomerSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class WhatsappBotService
{
  /**
   * Process an incoming WhatsApp message
   *
   * @param array $messageData
   * @return array
   */
  public function processIncomingMessage($messageData)
  {
    try {
      // Extract message details
      $connectionId = $messageData['connectionId'] ?? null;
      $storeWhatsappId = $this->extractStoreIdFromConnectionId($connectionId);

      if (!$storeWhatsappId) {
        Log::error('WhatsappBot: Invalid connection ID format', [
          'connectionId' => $connectionId
        ]);
        return [
          'success' => false,
          'message' => 'Invalid connection ID format'
        ];
      }

      // Get store from the connection ID
      $store = $this->getStoreFromWhatsappId($storeWhatsappId);

      if (!$store) {
        Log::error('WhatsappBot: Store not found for connection', [
          'connectionId' => $connectionId,
          'storeWhatsappId' => $storeWhatsappId
        ]);
        return [
          'success' => false,
          'message' => 'Store not found for this connection'
        ];
      }

      // Extract message content
      $messages = $messageData['data']['messages'] ?? [];

      foreach ($messages as $message) {
        // Skip if it's an outgoing message
        if ($message['key']['fromMe'] ?? false) {
          continue;
        }

        $phone = $message['key']['remoteJid'] ?? null;

        // Skip if no phone number
        if (!$phone) {
          continue;
        }

        // Clean up phone number (remove @s.whatsapp.net or similar)
        $phone = preg_replace('/\@.*$/', '', $phone);

        // Get message content
        $messageContent = $this->extractMessageContent($message);
        $messageType = $this->getMessageType($message);

        // Get or create customer session
        $session = $this->getOrCreateCustomerSession($store, $phone);

        // Save the incoming message
        $savedMessage = WhatsappBotMessage::create([
          'store_id' => $store->id,
          'phone_number' => $phone,
          'direction' => 'incoming',
          'message' => $messageContent,
          'message_type' => $messageType,
          'session_id' => $session->session_id,
          'context' => $session->current_state,
        ]);

        // Process the message based on context
        return $this->handleIncomingMessage($store, $savedMessage, $session);
      }

      return [
        'success' => true,
        'message' => 'No valid messages to process'
      ];
    } catch (\Exception $e) {
      Log::error('WhatsappBot: Error processing incoming message', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      return [
        'success' => false,
        'message' => 'Error processing message: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Handle the incoming message based on the content and session context
   *
   * @param Store $store
   * @param WhatsappBotMessage $message
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function handleIncomingMessage($store, $message, $session)
  {
    $messageContent = strtolower(trim($message->message));

    // If this is the first message, send welcome message
    if ($session->messages()->count() <= 1) {
      return $this->sendWelcomeMessage($store, $message->phone_number, $session);
    }

    // Check if the message is a command
    if (Str::startsWith($messageContent, '/') || Str::startsWith($messageContent, '!') || Str::startsWith($messageContent, '#')) {
      return $this->handleCommand($store, $messageContent, $message, $session);
    }

    // Otherwise, handle based on current context/state
    switch ($session->current_state) {
      case 'browsing':
        // In browsing state, assume they're searching for products
        return $this->handleProductSearch($store, $messageContent, $message->phone_number, $session);

      case 'viewing_product':
        // If they're viewing a product, check for action commands
        return $this->handleProductViewAction($store, $messageContent, $message->phone_number, $session);

      case 'checkout':
        // Handle checkout process
        return $this->handleCheckoutProcess($store, $messageContent, $message->phone_number, $session);

      default:
        // Default back to browsing
        return $this->handleBrowsingState($store, $messageContent, $message->phone_number, $session);
    }
  }

  /**
   * Send welcome message to the customer
   *
   * @param Store $store
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function sendWelcomeMessage($store, $phone, $session)
  {
    $welcomeMessage = "👋 *Selamat datang di {$store->name}!* 👋\n\n";
    $welcomeMessage .= "Saya adalah bot asisten belanja Anda. Berikut adalah yang dapat saya bantu:\n\n";
    $welcomeMessage .= "🔍 *Cari Produk*: Cukup kirim kata kunci pencarian untuk melihat produk yang cocok\n";
    $welcomeMessage .= "📋 */products*: Lihat semua produk yang tersedia\n";
    $welcomeMessage .= "ℹ️ */help*: Lihat semua perintah yang tersedia\n\n";
    $welcomeMessage .= "Beri tahu saya apa yang Anda cari hari ini!";

    // Send the welcome message
    $this->saveAndSendOutgoingMessage($store->id, $phone, $welcomeMessage, $session);

    // Update session state
    $session->current_state = 'browsing';
    $session->save();

    return [
      'success' => true,
      'message' => 'Welcome message sent'
    ];
  }

  /**
   * Handle a command message
   *
   * @param Store $store
   * @param string $commandText
   * @param WhatsappBotMessage $message
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function handleCommand($store, $commandText, $message, $session)
  {
    // Remove command prefix (/, !, #)
    $commandText = ltrim($commandText, '/!#');
    $parts = explode(' ', $commandText);
    $commandName = strtolower($parts[0]);

    // Remove the command name from parts, leaving only parameters
    array_shift($parts);
    $parameters = $parts;

    switch ($commandName) {
      case 'help':
        return $this->sendHelpMessage($store, $message->phone_number, $session);

      case 'products':
        // Check if we have a page parameter
        $page = isset($parameters[0]) && is_numeric($parameters[0]) ? (int)$parameters[0] : 1;
        return $this->sendProductList($store, $message->phone_number, $session, $page);

      case 'cart':
        return $this->sendCartSummary($store, $message->phone_number, $session);

      case 'checkout':
        return $this->initiateCheckout($store, $message->phone_number, $session);

      case 'clear':
        return $this->clearCart($store, $message->phone_number, $session);

      case 'cancel':
        return $this->cancelCurrentAction($store, $message->phone_number, $session);

      case 'add':
        // Format: /add product_id quantity
        $productId = isset($parameters[0]) ? (int)$parameters[0] : 0;
        $quantity = isset($parameters[1]) && is_numeric($parameters[1]) ? (int)$parameters[1] : 1;
        return $this->addToCart($store, $productId, $quantity, $message->phone_number, $session);

      case 'remove':
        // Format: /remove item_index (1-based)
        if (!isset($parameters[0]) || !is_numeric($parameters[0])) {
          $this->saveAndSendOutgoingMessage(
            $store->id,
            $message->phone_number,
            "Mohon tentukan item mana yang ingin dihapus (contoh: */remove 1*)",
            $session
          );
          return ['success' => false, 'message' => 'Missing item index parameter'];
        }

        $itemIndex = (int)$parameters[0] - 1; // Convert from 1-based to 0-based
        return $this->removeFromCart($store, $itemIndex, $message->phone_number, $session);

      case 'view':
        // Format: /view product_index (1-based)
        if (!isset($parameters[0]) || !is_numeric($parameters[0])) {
          $this->saveAndSendOutgoingMessage(
            $store->id,
            $message->phone_number,
            "Mohon tentukan produk mana yang ingin dilihat (contoh: */view 1*)",
            $session
          );
          return ['success' => false, 'message' => 'Missing product index parameter'];
        }

        return $this->viewProductDetails($store, $parameters[0], $message->phone_number, $session);

      default:
        // Check for custom commands in the database (store-specific or global)
        $command = \App\Models\WhatsappBotCommand::where('command', $commandName)
          ->forStore($store->id)
          ->active()
          ->orderByRaw('store_id IS NULL ASC') // Prioritize store-specific commands over globals
          ->first();

        if ($command) {
          return $this->executeCustomCommand($store, $command, $parameters, $message->phone_number, $session);
        }

        // Unknown command
        $this->saveAndSendOutgoingMessage(
          $store->id,
          $message->phone_number,
          "Maaf, saya tidak mengenali perintah tersebut. Ketik */help* untuk melihat perintah yang tersedia.",
          $session
        );

        return [
          'success' => true,
          'message' => 'Unknown command response sent'
        ];
    }
  }

  /**
   * Send help message with available commands
   *
   * @param Store $store
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function sendHelpMessage($store, $phone, $session)
  {
    $helpMessage = "📱 *Perintah yang Tersedia* 📱\n\n";
    $helpMessage .= "*/products* - Lihat semua produk\n";
    $helpMessage .= "*/cart* - Lihat keranjang belanja Anda\n";
    $helpMessage .= "*/checkout* - Lanjut ke pembayaran\n";
    $helpMessage .= "*/clear* - Kosongkan keranjang Anda\n";
    $helpMessage .= "*/cancel* - Batalkan aksi saat ini\n";
    $helpMessage .= "*/help* - Tampilkan pesan bantuan ini\n\n";

    // Add custom commands (store-specific and global)
    $customCommands = \App\Models\WhatsappBotCommand::forStore($store->id)
      ->active()
      ->ordered()
      ->get();

    if ($customCommands->count() > 0) {
      $helpMessage .= "*Perintah Kustom:*\n";

      foreach ($customCommands as $command) {
        // Add an indicator for global commands
        $isGlobal = $command->store_id === null;
        $commandDesc = "*/{$command->command}* - {$command->description}" . ($isGlobal ? " (Global)" : "");
        $helpMessage .= $commandDesc . "\n";
      }
    }

    $helpMessage .= "\nAnda juga bisa langsung ketik apa yang Anda cari untuk mencari produk kami!";

    // Send the message
    $this->saveAndSendOutgoingMessage($store->id, $phone, $helpMessage, $session);

    return [
      'success' => true,
      'message' => 'Help message sent'
    ];
  }

  /**
   * Send a list of products
   *
   * @param Store $store
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @param int $page
   * @return array
   */
  protected function sendProductList($store, $phone, $session, $page = 1)
  {
    $perPage = 5; // Number of products per message
    $products = $store->products()
      ->where('status', 'active')
      ->orderBy('title')
      ->skip(($page - 1) * $perPage)
      ->take($perPage + 1) // Take one extra to check if there are more
      ->get();

    $totalProducts = $store->products()->where('status', 'active')->count();

    if ($products->isEmpty()) {
      $this->saveAndSendOutgoingMessage(
        $store->id,
        $phone,
        "Belum ada produk di toko kami. Silakan periksa kembali nanti!",
        $session
      );

      return [
        'success' => true,
        'message' => 'Empty product list message sent'
      ];
    }

    // Check if there are more products
    $hasMoreProducts = $products->count() > $perPage;

    // Remove the extra product if there are more
    if ($hasMoreProducts) {
      $products = $products->slice(0, $perPage);
    }

    $message = "🛍️ *Produk (" . ($page > 1 ? "Halaman {$page}" : "Menampilkan 1-{$perPage} dari {$totalProducts}") . ")* 🛍️\n\n";

    foreach ($products as $index => $product) {
      $productNumber = ($page - 1) * $perPage + $index + 1;
      $price = number_format($product->price, 2);

      $message .= "*{$productNumber}. {$product->title}*\n";
      $message .= "💰 Harga: Rp {$price}\n";

      if ($product->highlight) {
        $message .= "✨ {$product->highlight}\n";
      }

      $message .= "Ketik: */view {$productNumber}* untuk detail\n\n";
    }

    if ($hasMoreProducts) {
      $nextPage = $page + 1;
      $message .= "Untuk melihat produk lebih banyak, ketik: */products {$nextPage}*";
    }

    // Send the message
    $this->saveAndSendOutgoingMessage($store->id, $phone, $message, $session);

    // Update session context
    $session->current_state = 'browsing';
    $session->context_data = [
      'product_list_page' => $page,
      'products' => $products->pluck('id')->toArray()
    ];
    $session->save();

    return [
      'success' => true,
      'message' => 'Product list sent'
    ];
  }

  /**
   * Handle product search
   *
   * @param Store $store
   * @param string $query
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function handleProductSearch($store, $query, $phone, $session)
  {
    // Search for products based on the query
    $products = $store->products()
      ->where('status', 'active')
      ->where(function ($q) use ($query) {
        $q->where('title', 'like', '%' . $query . '%')
          ->orWhere('description', 'like', '%' . $query . '%')
          ->orWhere('highlight', 'like', '%' . $query . '%');
      })
      ->take(5)
      ->get();

    if ($products->isEmpty()) {
      $this->saveAndSendOutgoingMessage(
        $store->id,
        $phone,
        "Maaf, saya tidak menemukan produk yang cocok dengan '{$query}'. Coba kata kunci lain atau ketik */products* untuk melihat semua produk.",
        $session
      );

      return [
        'success' => true,
        'message' => 'No products found message sent'
      ];
    }

    $message = "🔍 *Hasil Pencarian untuk '{$query}'* 🔍\n\n";

    foreach ($products as $index => $product) {
      $price = number_format($product->price, 2);

      $message .= "*" . ($index + 1) . ". {$product->title}*\n";
      $message .= "💰 Harga: Rp {$price}\n";

      if ($product->highlight) {
        $message .= "✨ {$product->highlight}\n";
      }

      $message .= "Ketik: */view " . ($index + 1) . "* untuk detail\n\n";
    }

    $message .= "Ingin melihat lebih banyak produk? Ketik */products* untuk melihat semua.";

    // Send the message
    $this->saveAndSendOutgoingMessage($store->id, $phone, $message, $session);

    // Update session context
    $session->current_state = 'browsing';
    $session->context_data = [
      'search_query' => $query,
      'search_results' => $products->pluck('id')->toArray()
    ];
    $session->save();

    return [
      'success' => true,
      'message' => 'Search results sent'
    ];
  }

  /**
   * Handle browsing state actions
   *
   * @param Store $store
   * @param string $messageContent
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function handleBrowsingState($store, $messageContent, $phone, $session)
  {
    // In browsing state, treat as a search query
    return $this->handleProductSearch($store, $messageContent, $phone, $session);
  }

  /**
   * Handle actions when viewing a product
   *
   * @param Store $store
   * @param string $messageContent
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function handleProductViewAction($store, $messageContent, $phone, $session)
  {
    // Get current product being viewed
    $contextData = $session->context_data ?? [];
    $productId = $contextData['product_id'] ?? null;

    if (!$productId) {
      // If no product ID in context, reset to browsing
      $session->current_state = 'browsing';
      $session->save();

      $this->saveAndSendOutgoingMessage(
        $store->id,
        $phone,
        "Mari temukan apa yang Anda cari. Ketik */products* untuk melihat semua produk atau cari dengan mengetikkan nama produk.",
        $session
      );

      return [
        'success' => true,
        'message' => 'Reset to browsing state'
      ];
    }

    // Check if it's a quantity for adding to cart
    if (is_numeric($messageContent)) {
      $quantity = (int)$messageContent;
      if ($quantity > 0) {
        return $this->addToCart($store, $productId, $quantity, $phone, $session);
      }
    }

    // Default: treat as a new search
    $session->current_state = 'browsing';
    $session->save();

    return $this->handleProductSearch($store, $messageContent, $phone, $session);
  }

  /**
   * Handle checkout process
   *
   * @param Store $store
   * @param string $messageContent
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function handleCheckoutProcess($store, $messageContent, $phone, $session)
  {
    // Get checkout step from session
    $contextData = $session->context_data ?? [];
    $step = $contextData['checkout_step'] ?? 'name';
    $checkoutData = $contextData['checkout_data'] ?? ['total' => 0];

    switch ($step) {
      case 'name':
        // Save the customer name and ask for address
        $checkoutData['customer_name'] = $messageContent;

        $this->saveAndSendOutgoingMessage(
          $store->id,
          $phone,
          "Terima kasih, {$messageContent}!\n\n" .
            "Silakan masukkan alamat pengiriman Anda:",
          $session
        );

        // Update session context
        $contextData['checkout_step'] = 'address';
        $contextData['checkout_data'] = $checkoutData;
        $session->context_data = $contextData;
        $session->save();

        return [
          'success' => true,
          'message' => 'Checkout name received, asked for address'
        ];

      case 'address':
        // Save the address and ask for payment method
        $checkoutData['address'] = $messageContent;

        $this->saveAndSendOutgoingMessage(
          $store->id,
          $phone,
          "Alamat pengiriman diterima!\n\n" .
            "Silakan pilih metode pembayaran Anda:\n" .
            "1️⃣ Transfer Bank\n" .
            "2️⃣ Bayar di Tempat (COD)\n" .
            "3️⃣ E-Wallet\n\n" .
            "Balas dengan nomor pilihan Anda (1-3).",
          $session
        );

        // Update session context
        $contextData['checkout_step'] = 'payment_method';
        $contextData['checkout_data'] = $checkoutData;
        $session->context_data = $contextData;
        $session->save();

        return [
          'success' => true,
          'message' => 'Checkout address received, asked for payment method'
        ];

      case 'payment_method':
        // Process payment method choice
        $paymentMethod = '';
        $validPayment = false;

        if (
          preg_match('/^[123]$/', trim($messageContent)) ||
          stripos($messageContent, 'bank') !== false ||
          stripos($messageContent, 'cash') !== false ||
          stripos($messageContent, 'e-wallet') !== false ||
          stripos($messageContent, 'ewallet') !== false
        ) {

          $validPayment = true;

          if ($messageContent == '1' || stripos($messageContent, 'bank') !== false) {
            $paymentMethod = 'Transfer Bank';
          } elseif ($messageContent == '2' || stripos($messageContent, 'cash') !== false) {
            $paymentMethod = 'Bayar di Tempat (COD)';
          } else {
            $paymentMethod = 'E-Wallet';
          }
        }

        if (!$validPayment) {
          $this->saveAndSendOutgoingMessage(
            $store->id,
            $phone,
            "Maaf, saya tidak mengerti pilihan pembayaran Anda.\n\n" .
              "Silakan pilih metode pembayaran Anda:\n" .
              "1️⃣ Transfer Bank\n" .
              "2️⃣ Bayar di Tempat (COD)\n" .
              "3️⃣ E-Wallet\n\n" .
              "Balas dengan nomor pilihan Anda (1-3).",
            $session
          );

          return [
            'success' => false,
            'message' => 'Invalid payment method choice'
          ];
        }

        // Save payment method and complete the order
        $checkoutData['payment_method'] = $paymentMethod;

        // Create an order in the system
        $orderId = $this->createOrder($store, $phone, $checkoutData, $session);

        // Send confirmation message
        $this->saveAndSendOutgoingMessage(
          $store->id,
          $phone,
          "🎉 *Terima Kasih atas Pesanan Anda!* 🎉\n\n" .
            "Pesanan #" . str_pad($orderId, 6, '0', STR_PAD_LEFT) . " telah dikonfirmasi.\n\n" .
            "*Detail Pesanan:*\n" .
            "Nama: " . $checkoutData['customer_name'] . "\n" .
            "Alamat: " . $checkoutData['address'] . "\n" .
            "Pembayaran: " . $paymentMethod . "\n" .
            "Total: Rp " . number_format($checkoutData['total'], 2) . "\n\n" .
            "Kami akan segera memproses pesanan Anda dan menghubungi Anda untuk detail lebih lanjut.\n\n" .
            "Ketik */help* untuk melihat perintah yang tersedia.",
          $session
        );

        // Reset cart and session state
        $session->cart_data = ['items' => []];
        $session->current_state = 'browsing';
        $session->context_data = null;
        $session->save();

        return [
          'success' => true,
          'message' => 'Order completed successfully'
        ];

      default:
        // If we get here, something went wrong with the checkout state
        $this->saveAndSendOutgoingMessage(
          $store->id,
          $phone,
          "Maaf, terjadi masalah dengan proses pembayaran. Silakan coba lagi dengan mengetik */checkout*.",
          $session
        );

        // Reset to browsing state
        $session->current_state = 'browsing';
        $session->context_data = null;
        $session->save();

        return [
          'success' => false,
          'message' => 'Invalid checkout step'
        ];
    }
  }

  /**
   * Send cart summary
   *
   * @param Store $store
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function sendCartSummary($store, $phone, $session)
  {
    // Get cart data from session
    $cartData = $session->cart_data ?? ['items' => []];
    $items = $cartData['items'] ?? [];

    if (empty($items)) {
      $this->saveAndSendOutgoingMessage(
        $store->id,
        $phone,
        "🛒 *Keranjang Belanja Anda* 🛒\n\n" .
          "Keranjang Anda saat ini kosong.\n\n" .
          "Ketik */products* untuk melihat produk kami dan tambahkan ke keranjang Anda.",
        $session
      );

      return [
        'success' => true,
        'message' => 'Empty cart message sent'
      ];
    }

    // Calculate total price
    $totalPrice = 0;
    $message = "🛒 *Keranjang Belanja Anda* 🛒\n\n";

    foreach ($items as $index => $item) {
      $product = Product::find($item['product_id']);

      if (!$product) {
        continue; // Skip if product no longer exists
      }

      $quantity = $item['quantity'] ?? 1;
      $price = $product->price * $quantity;
      $totalPrice += $price;

      $message .= "*" . ($index + 1) . ". {$product->title}*\n";
      $message .= "   Jumlah: {$quantity}\n";
      $message .= "   Harga: Rp " . number_format($price, 2) . "\n";
      $message .= "   _Untuk menghapus: */remove " . ($index + 1) . "*_\n\n";
    }

    $message .= "*Total: Rp " . number_format($totalPrice, 2) . "*\n\n";

    $message .= "✅ Ketik */checkout* untuk melanjutkan pesanan Anda\n";
    $message .= "❌ Ketik */clear* untuk mengosongkan keranjang Anda\n";
    $message .= "🛍️ Ketik */products* untuk lanjut berbelanja";

    $this->saveAndSendOutgoingMessage($store->id, $phone, $message, $session);

    return [
      'success' => true,
      'message' => 'Cart summary sent'
    ];
  }

  /**
   * Add a product to cart
   *
   * @param Store $store
   * @param int $productId
   * @param int $quantity
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function addToCart($store, $productId, $quantity, $phone, $session)
  {
    // Find the product
    $product = $store->products()->where('id', $productId)->where('status', 'active')->first();

    if (!$product) {
      $this->saveAndSendOutgoingMessage(
        $store->id,
        $phone,
        "Maaf, saya tidak menemukan produk tersebut. Ketik */products* untuk melihat produk yang tersedia.",
        $session
      );

      return [
        'success' => false,
        'message' => 'Product not found'
      ];
    }

    // Get or initialize cart data
    $cartData = $session->cart_data ?? ['items' => []];
    $items = $cartData['items'] ?? [];

    // Check if product already exists in cart
    $existingItem = false;
    foreach ($items as &$item) {
      if ($item['product_id'] == $product->id) {
        $item['quantity'] = ($item['quantity'] ?? 1) + $quantity;
        $existingItem = true;
        break;
      }
    }

    if (!$existingItem) {
      // Add new item to cart
      $items[] = [
        'product_id' => $product->id,
        'quantity' => $quantity,
        'added_at' => now()->toDateTimeString()
      ];
    }

    // Update cart data in session
    $cartData['items'] = $items;
    $cartData['updated_at'] = now()->toDateTimeString();
    $session->cart_data = $cartData;
    $session->save();

    // Send confirmation message
    $this->saveAndSendOutgoingMessage(
      $store->id,
      $phone,
      "✅ Ditambahkan ke keranjang: *{$product->title}* x {$quantity}\n\n" .
        "Ketik */cart* untuk melihat keranjang Anda atau */checkout* untuk melanjutkan pesanan Anda.",
      $session
    );

    return [
      'success' => true,
      'message' => 'Product added to cart'
    ];
  }

  /**
   * Remove a product from cart
   *
   * @param Store $store
   * @param int $itemIndex
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function removeFromCart($store, $itemIndex, $phone, $session)
  {
    // Get cart data
    $cartData = $session->cart_data ?? ['items' => []];
    $items = $cartData['items'] ?? [];

    // Check if the item index is valid
    if ($itemIndex < 0 || $itemIndex >= count($items)) {
      $this->saveAndSendOutgoingMessage(
        $store->id,
        $phone,
        "Maaf, saya tidak menemukan item tersebut di keranjang Anda. Ketik */cart* untuk melihat keranjang Anda saat ini.",
        $session
      );

      return [
        'success' => false,
        'message' => 'Invalid cart item index'
      ];
    }

    // Get the item to remove for the confirmation message
    $removedItem = $items[$itemIndex];
    $product = Product::find($removedItem['product_id']);
    $productName = $product ? $product->title : 'Product';

    // Remove the item from the cart
    array_splice($items, $itemIndex, 1);

    // Update cart data in session
    $cartData['items'] = $items;
    $cartData['updated_at'] = now()->toDateTimeString();
    $session->cart_data = $cartData;
    $session->save();

    // Send confirmation message
    $this->saveAndSendOutgoingMessage(
      $store->id,
      $phone,
      "🗑️ Dihapus dari keranjang: *{$productName}*\n\n" .
        "Ketik */cart* untuk melihat keranjang yang telah diperbarui.",
      $session
    );

    return [
      'success' => true,
      'message' => 'Product removed from cart'
    ];
  }

  /**
   * Clear the shopping cart
   *
   * @param Store $store
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function clearCart($store, $phone, $session)
  {
    // Reset cart data
    $session->cart_data = ['items' => []];
    $session->save();

    // Send confirmation message
    $this->saveAndSendOutgoingMessage(
      $store->id,
      $phone,
      "🗑️ Keranjang Anda telah dikosongkan.\n\n" .
        "Ketik */products* untuk melihat produk kami dan mulai pesanan baru.",
      $session
    );

    return [
      'success' => true,
      'message' => 'Cart cleared'
    ];
  }

  /**
   * Initiate the checkout process
   *
   * @param Store $store
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function initiateCheckout($store, $phone, $session)
  {
    // Get cart data
    $cartData = $session->cart_data ?? ['items' => []];
    $items = $cartData['items'] ?? [];

    if (empty($items)) {
      $this->saveAndSendOutgoingMessage(
        $store->id,
        $phone,
        "Keranjang Anda kosong! Tambahkan beberapa produk sebelum checkout.\n\n" .
          "Ketik */products* untuk melihat produk kami.",
        $session
      );

      return [
        'success' => false,
        'message' => 'Empty cart, cannot checkout'
      ];
    }

    // Calculate total price and build checkout summary
    $totalPrice = 0;
    $message = "💳 *Ringkasan Checkout* 💳\n\n";

    foreach ($items as $index => $item) {
      $product = Product::find($item['product_id']);

      if (!$product) {
        continue; // Skip if product no longer exists
      }

      $quantity = $item['quantity'] ?? 1;
      $price = $product->price * $quantity;
      $totalPrice += $price;

      $message .= "*{$product->title}* x {$quantity}\n";
      $message .= "Harga: Rp " . number_format($price, 2) . "\n\n";
    }

    $message .= "*Total: Rp " . number_format($totalPrice, 2) . "*\n\n";

    $message .= "Untuk menyelesaikan pesanan Anda, silakan berikan:\n\n";
    $message .= "1️⃣ Nama lengkap Anda\n";
    $message .= "2️⃣ Alamat pengiriman\n";
    $message .= "3️⃣ Metode pembayaran yang diinginkan\n\n";

    $message .= "Silakan ketik nama lengkap Anda untuk melanjutkan:";

    $this->saveAndSendOutgoingMessage($store->id, $phone, $message, $session);

    // Update session state to checkout
    $session->current_state = 'checkout';
    $session->context_data = [
      'checkout_step' => 'name',
      'checkout_data' => [
        'total' => $totalPrice
      ]
    ];
    $session->save();

    return [
      'success' => true,
      'message' => 'Checkout initiated'
    ];
  }

  /**
   * Cancel the current action/workflow
   *
   * @param Store $store
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function cancelCurrentAction($store, $phone, $session)
  {
    // Get current state to provide context-specific messages
    $currentState = $session->current_state;

    // Reset to browsing state
    $session->current_state = 'browsing';
    $session->context_data = null;
    $session->save();

    // Send confirmation message
    $this->saveAndSendOutgoingMessage(
      $store->id,
      $phone,
      "✅ Tindakan dibatalkan. Apa yang ingin Anda lakukan sekarang?\n\n" .
        "Ketik */help* untuk melihat perintah yang tersedia.",
      $session
    );

    return [
      'success' => true,
      'message' => 'Action canceled'
    ];
  }

  /**
   * Execute a custom command
   *
   * @param Store $store
   * @param WhatsappBotCommand $command
   * @param array $parameters
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function executeCustomCommand($store, $command, $parameters, $phone, $session)
  {
    // Get the response template
    $response = $command->response_template;

    // Replace parameters in the response if any
    if (!empty($parameters) && !empty($command->parameters)) {
      $paramMap = $command->parameters;

      foreach ($paramMap as $index => $paramName) {
        $value = $parameters[$index] ?? '';
        $response = str_replace('{{' . $paramName . '}}', $value, $response);
      }
    }

    // Replace any store variables
    $response = str_replace('{{store_name}}', $store->name, $response);

    // Send the response
    $this->saveAndSendOutgoingMessage($store->id, $phone, $response, $session);

    return [
      'success' => true,
      'message' => 'Custom command executed'
    ];
  }

  /**
   * View product details
   *
   * @param Store $store
   * @param int $productIndex
   * @param string $phone
   * @param WhatsappCustomerSession $session
   * @return array
   */
  protected function viewProductDetails($store, $productIndex, $phone, $session)
  {
    // Get the context data to find the right product
    $contextData = $session->context_data ?? [];
    $productIds = [];

    // Check if we're in a search results context or viewing all products
    if (isset($contextData['search_results'])) {
      $productIds = $contextData['search_results'];
    } elseif (isset($contextData['products'])) {
      $productIds = $contextData['products'];
    } else {
      // Fallback to all products
      $page = isset($contextData['product_list_page']) ? (int)$contextData['product_list_page'] : 1;
      $perPage = 5;

      $productIds = $store->products()
        ->where('status', 'active')
        ->orderBy('title')
        ->skip(($page - 1) * $perPage)
        ->take($perPage)
        ->pluck('id')
        ->toArray();
    }

    // Convert from 1-based to 0-based index
    $index = (int)$productIndex - 1;

    // Check if index is valid
    if ($index < 0 || $index >= count($productIds)) {
      $this->saveAndSendOutgoingMessage(
        $store->id,
        $phone,
        "Maaf, saya tidak menemukan produk tersebut. Silakan coba lagi dengan nomor produk yang valid.",
        $session
      );

      return [
        'success' => false,
        'message' => 'Invalid product index'
      ];
    }

    // Get the product
    $productId = $productIds[$index];
    $product = $store->products()->where('id', $productId)->where('status', 'active')->first();

    if (!$product) {
      $this->saveAndSendOutgoingMessage(
        $store->id,
        $phone,
        "Maaf, produk tersebut tidak tersedia lagi.",
        $session
      );

      return [
        'success' => false,
        'message' => 'Product not found'
      ];
    }

    // Build the product details message
    $message = "🛍️ *{$product->title}* 🛍️\n\n";

    if ($product->description) {
      $message .= "{$product->description}\n\n";
    }

    $message .= "💰 *Harga:* Rp " . number_format($product->price, 2) . "\n";

    if ($product->stock > 0) {
      $message .= "✅ *Stok Tersedia:* {$product->stock} tersedia\n\n";
    } else {
      $message .= "❌ *Stok Kosong*\n\n";
    }

    // Add shopping cart instructions
    $message .= "Untuk menambahkan ke keranjang Anda, ketik:\n";
    $message .= "*/add {$product->id} [jumlah]*\n\n";

    // Add navigation options
    $message .= "Ketik */products* untuk melihat lebih banyak produk\n";
    $message .= "Ketik */cart* untuk melihat keranjang Anda";

    // Send the message
    $this->saveAndSendOutgoingMessage($store->id, $phone, $message, $session);

    // Update session state
    $session->current_state = 'viewing_product';
    $session->context_data = [
      'product_id' => $product->id,
      'previous_context' => $contextData
    ];
    $session->save();

    return [
      'success' => true,
      'message' => 'Product details sent'
    ];
  }

  /**
   * Create an order in the system
   *
   * @param Store $store
   * @param string $phone
   * @param array $checkoutData
   * @param WhatsappCustomerSession $session
   * @return int Order ID
   */
  protected function createOrder($store, $phone, $checkoutData, $session)
  {
    try {
      // Get cart data
      $cartData = $session->cart_data ?? ['items' => []];
      $items = $cartData['items'] ?? [];

      // Start a database transaction
      \DB::beginTransaction();

      // Check if we need to create a customer account
      $customer = \App\Models\WhatsappCustomer::firstOrCreate(
        [
          'phone_number' => $phone,
          'store_id' => $store->id
        ],
        [
          'name' => $checkoutData['customer_name'] ?? 'Pelanggan',
          'address' => $checkoutData['address'] ?? null,
          'last_interaction_at' => now()
        ]
      );

      // Create the order
      $order = new \App\Models\TransactionWhatsapp([
        'store_id' => $store->id,
        'whatsapp_customer_id' => $customer->id,
        'order_number' => 'WA-' . time(),
        'status' => 'pending',
        'payment_status' => 'pending',
        'payment_method' => $checkoutData['payment_method'] ?? 'WhatsApp',
        'total' => $checkoutData['total'] ?? 0,
        'notes' => 'Pesanan melalui Bot WhatsApp',
        'shipping_address' => $checkoutData['address'] ?? '',
        'source' => 'whatsapp_bot'
      ]);

      $order->save();

      // Add order items
      $orderItems = [];

      foreach ($items as $item) {
        $product = \App\Models\Product::find($item['product_id']);

        if (!$product) continue;

        $quantity = $item['quantity'] ?? 1;
        $price = $product->price * $quantity;

        $orderItem = new \App\Models\TransactionWhatsappItem([
          'transaction_whatsapp_id' => $order->id,
          'product_id' => $product->id,
          'quantity' => $quantity,
          'price' => $product->price,
          'total' => $price
        ]);

        $orderItem->save();
        $orderItems[] = $orderItem;
      }

      // Update order with correct total from order items if necessary
      if (count($orderItems) > 0) {
        $calculatedTotal = array_sum(array_map(function ($item) {
          return $item->total;
        }, $orderItems));

        $order->total = $calculatedTotal;
        $order->save();
      }

      // Commit transaction
      \DB::commit();

      return $order->id;
    } catch (\Exception $e) {
      // Rollback on error
      \DB::rollBack();

      Log::error('WhatsappBot: Error creating order', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'store_id' => $store->id,
        'phone' => $phone
      ]);

      // Return a dummy order ID in case of error
      return rand(1000, 9999);
    }
  }

  /**
   * Saves and sends an outgoing message
   *
   * @param int $storeId
   * @param string $phone
   * @param string $message
   * @param WhatsappCustomerSession $session
   * @return WhatsappBotMessage
   */
  protected function saveAndSendOutgoingMessage($storeId, $phone, $message, $session)
  {
    // Save the outgoing message to the database
    $botMessage = WhatsappBotMessage::create([
      'store_id' => $storeId,
      'phone_number' => $phone,
      'direction' => 'outgoing',
      'message' => $message,
      'message_type' => 'text',
      'session_id' => $session->session_id,
      'context' => $session->current_state,
    ]);

    // Send the message via WhatsApp gateway
    // For now, we're just simulating sending, but this would call the WhatsApp Gateway API
    $this->sendMessageViaGateway($storeId, $phone, $message);

    return $botMessage;
  }

  /**
   * Send message via WhatsApp gateway
   *
   * @param int $storeId
   * @param string $phone
   * @param string $message
   * @return bool
   */
  protected function sendMessageViaGateway($storeId, $phone, $message)
  {
    try {
      // Get the store's WhatsApp connection
      $store = Store::findOrFail($storeId);
      $whatsapp = $store->whatsapp;

      if (!$whatsapp || $whatsapp->status !== 'connected') {
        Log::error('WhatsappBot: Cannot send message, store WhatsApp not connected', [
          'storeId' => $storeId
        ]);
        return false;
      }

      // Format the connection ID as CID_{id} for the NodeJS gateway
      $connectionId = 'CID_' . $whatsapp->id;

      // Make API request to the NodeJS WhatsApp gateway to send the message
      $gatewayUrl = env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000');
      $client = new \GuzzleHttp\Client();

      $response = $client->post("{$gatewayUrl}/api/send-message", [
        'json' => [
          'connectionId' => $connectionId,
          'number' => $phone,
          'message' => $message
        ],
        'headers' => [
          'Authorization' => 'Bearer ' . env('WHATSAPP_GATEWAY_API_KEY', 'simple_api_token_123'),
          'Content-Type' => 'application/json'
        ]
      ]);

      if ($response->getStatusCode() == 200) {
        return true;
      }

      Log::error('WhatsappBot: Failed to send message via gateway', [
        'storeId' => $storeId,
        'statusCode' => $response->getStatusCode(),
        'response' => (string) $response->getBody()
      ]);

      return false;
    } catch (\Exception $e) {
      Log::error('WhatsappBot: Error sending message via gateway', [
        'storeId' => $storeId,
        'error' => $e->getMessage()
      ]);

      return false;
    }
  }

  /**
   * Extract store ID from connection ID
   *
   * @param string $connectionId
   * @return int|null
   */
  protected function extractStoreIdFromConnectionId($connectionId)
  {
    if (!$connectionId || !Str::startsWith($connectionId, 'CID_')) {
      return null;
    }

    return (int) substr($connectionId, 4);
  }

  /**
   * Get store from WhatsApp ID
   *
   * @param int $whatsappId
   * @return Store|null
   */
  protected function getStoreFromWhatsappId($whatsappId)
  {
    $storeWhatsapp = \App\Models\StoreWhatsapp::find($whatsappId);

    if (!$storeWhatsapp) {
      return null;
    }

    return $storeWhatsapp->store;
  }

  /**
   * Get or create a customer session
   *
   * @param Store $store
   * @param string $phone
   * @return WhatsappCustomerSession
   */
  protected function getOrCreateCustomerSession($store, $phone)
  {
    $session = $store->whatsappCustomerSessions()
      ->where('phone_number', $phone)
      ->where('is_active', true)
      ->first();

    if (!$session) {
      // Create a new session
      $session = $store->whatsappCustomerSessions()->create([
        'phone_number' => $phone,
        'session_id' => Str::uuid()->toString(),
        'current_state' => 'browsing',
        'last_interaction_at' => now(),
        'is_active' => true,
        'cart_data' => ['items' => []]
      ]);
    } else {
      // Update last interaction time
      $session->last_interaction_at = now();
      $session->save();
    }

    return $session;
  }

  /**
   * Extract message content from WhatsApp message object
   *
   * @param array $message
   * @return string
   */
  protected function extractMessageContent($message)
  {
    // Check for text message
    if (isset($message['message']['conversation'])) {
      return $message['message']['conversation'];
    }

    // Check for extended text message
    if (isset($message['message']['extendedTextMessage']['text'])) {
      return $message['message']['extendedTextMessage']['text'];
    }

    // Check for button response
    if (isset($message['message']['buttonsResponseMessage']['selectedDisplayText'])) {
      return $message['message']['buttonsResponseMessage']['selectedDisplayText'];
    }

    // Check for list response
    if (isset($message['message']['listResponseMessage']['title'])) {
      return $message['message']['listResponseMessage']['title'];
    }

    // For media messages, return caption if available or a placeholder
    if (isset($message['message']['imageMessage']['caption'])) {
      return $message['message']['imageMessage']['caption'];
    }

    // For other message types that we can't extract text from
    return '[Konten pesan tidak dapat diekstrak]';
  }

  /**
   * Get message type from WhatsApp message object
   *
   * @param array $message
   * @return string
   */
  protected function getMessageType($message)
  {
    if (isset($message['message']['conversation']) || isset($message['message']['extendedTextMessage'])) {
      return 'text';
    }

    if (isset($message['message']['imageMessage'])) {
      return 'image';
    }

    if (isset($message['message']['documentMessage'])) {
      return 'document';
    }

    if (isset($message['message']['audioMessage'])) {
      return 'audio';
    }

    if (isset($message['message']['videoMessage'])) {
      return 'video';
    }

    if (isset($message['message']['stickerMessage'])) {
      return 'sticker';
    }

    if (isset($message['message']['locationMessage'])) {
      return 'location';
    }

    if (isset($message['message']['contactMessage'])) {
      return 'contact';
    }

    // Default fallback
    return 'unknown';
  }
}
