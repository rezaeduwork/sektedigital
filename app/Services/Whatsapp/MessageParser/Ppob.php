<?php

namespace App\Services\Whatsapp\MessageParser;

use App\Models\Store;
use App\Models\Payment;
use App\Models\StoreProductInstant;
use App\Models\TransactionWhatsapp;
use App\Models\WhatsappCustomerSession;
use Illuminate\Support\Facades\Log;
use \App\Services\Whatsapp\MessageParser\Store as StoreParser;

class Ppob
{
  protected $store;
  protected $session;
  protected $selectedBrand;
  protected $selectedProduct;
  protected $selectedPaymentMethod;
  protected $transactionId;

  protected $storeParser;

  public function __construct($store = null, $session = null)
  {
    $this->store = $store;
    $this->session = $session;

    // PARSER CLASS
    $this->storeParser = new StoreParser($this->store);
  }

  /**
   * Help info
   */
  public function help()
  {
    return "🏪 " . $this->storeParser->name() . "\n" .
      "꘎━━━━━━━━━━━━━━━━━━━━━━━━━꘎\n" .
      $this->storeParser->time() . "\n" .
      "꘎━━━━━━━━━━━━━━━━━━━━━━━━━꘎\n\n" .
      "⚜ *Menu yang Tersedia* ⚜\n" .
      "⤷ *game* - Top Up Game\n" .
      "⤷ *pulsa* - Top Up Pulsa\n" .
      "⤷ *data* - Beli paket Data\n\n" .
      "Pilih layanan dengan mengetikkan perintah di atas.";
  }

  /**
   * Get list of available game brands for top-up
   */
  public function gameBrandList()
  {
    $products = StoreProductInstant::where('store_id', $this->store->id)
      ->where('category', 'Games')
      ->select('brand')
      ->distinct()
      ->get();

    if ($products->isEmpty()) {
      return "Tidak ada produk game tersedia saat ini.";
    }

    $newSessionContext = $this->session->context_data ?? [];
    $newSessionContext['selected_feature'] = "game";
    $response = "🎮 *Top Up Game*\n\n";
    $response .= "Silakan pilih game yang ingin di-top up:\n\n";
    foreach ($products as $index => $product) {
      $response .= ($index + 1) . ". " . $product->brand . "\n";

      // Store brand list in session for future reference
      if (!isset($this->session->context_data['brands'])) {
        $newSessionContext['brands'] = [];
      }
      $newSessionContext['brands'][$index + 1] = $product->brand;
    }
    $this->session->context_data = $newSessionContext;
    // Save updated session
    $this->session->save();

    return $response . "\nKetik *select [number]* untuk melanjutkan.\nContoh: *select 1*";
  }

  /**
   * Get list of available pulsa brands for top-up
   */
  public function pulsaBrandList()
  {
    $products = StoreProductInstant::where('store_id', $this->store->id)
      ->where('category', 'pulsa')
      ->select('brand')
      ->distinct()
      ->get();

    if ($products->isEmpty()) {
      return "Tidak ada produk pulsa tersedia saat ini.";
    }

    $response = "";
    foreach ($products as $index => $product) {
      $response .= ($index + 1) . ". " . $product->brand . "\n";

      // Store brand list in session for future reference
      if (!isset($this->session->context_data['brands'])) {
        $this->session->context_data['brands'] = [];
      }
      $this->session->context_data['brands'][$index + 1] = $product->brand;
    }

    // Save updated session
    $this->session->save();

    return $response;
  }

  /**
   * Get list of available data brands
   */
  public function dataBrandList()
  {
    $products = StoreProductInstant::where('store_id', $this->store->id)
      ->where('category', 'data')
      ->select('brand')
      ->distinct()
      ->get();

    if ($products->isEmpty()) {
      return "Tidak ada produk paket data tersedia saat ini.";
    }

    $response = "";
    foreach ($products as $index => $product) {
      $response .= ($index + 1) . ". " . $product->brand . "\n";

      // Store brand list in session for future reference
      if (!isset($this->session->context_data['brands'])) {
        $this->session->context_data['brands'] = [];
      }
      $this->session->context_data['brands'][$index + 1] = $product->brand;
    }

    // Save updated session
    $this->session->save();

    return $response;
  }

  /**
   * Get products for a selected brand
   */
  public function productList()
  {
    if (
      !isset($this->session->context_data['brands']) ||
      !isset($this->session->context_data['selected_index'])
    ) {
      return "Silakan pilih brand terlebih dahulu.\n";
    }
    $sessionContext = $this->session->context_data ?? [];

    Log::info('Game brand list retrieved', [
      'store_id' => $this->store->id,
      'brands' => $sessionContext['brands'],
      'session' => $this->session
    ]);

    $selectedIndex = $this->session->context_data['selected_index'];
    $selectedBrand = $this->session->context_data['brands'][$selectedIndex] ?? null;

    if (!$selectedBrand) {
      return "Brand tidak ditemukan.\n";
    }

    // Store the selected brand
    $sessionContext['selected_brand'] = $selectedBrand;

    // Get products for the selected brand
    $products = StoreProductInstant::where('store_id', $this->store->id)
      ->where('brand', $selectedBrand)
      ->orderBy('selling_price')
      ->get();

    if ($products->isEmpty()) {
      return "Tidak ada produk tersedia untuk brand ini.\n";
    }

    $response = "•==== *" . $selectedBrand . "* ====•\n\n";
    if ($products->count() > 0) {
      foreach ($products as $index => $product) {
        $response .= "▶ Kode: *" . $product->code . "*\n";
        $response .= "Produk: " . $product->title . "\n";
        $response .= "Harga: Rp " . number_format($product->selling_price, 0, ',', '.') . "\n\n";

        // Store product codes in session
        if (!isset($sessionContext['products'])) {
          $sessionContext['products'] = [];
        }
        $sessionContext['products'][$product->code] = [
          'id' => $product->id,
          'title' => $product->title,
          'price' => $product->selling_price
        ];
      }
    } else {
      $response .= "Produk tidak tersedia saat ini.\n";
    }
    $this->session->context_data = $sessionContext;
    // Save the updated session
    $this->session->save();

    return $response . "Ketik *p [code]* untuk memilih pembayaran.\nContoh: *p " . ($product->code ?? 'ml100') . "*";
  }

  /**
   * Get product detail for selected code
   */
  public function productDetail()
  {
    if (!isset($this->session->context_data['code'])) {
      return "Silakan pilih produk terlebih dahulu.";
    }

    $sessionContext = $this->session->context_data ?? [];

    $selectedCode = $this->session->context_data['code'];

    if (!$selectedCode) {
      return "Produk tidak ditemukan. Silakan pilih kembali.";
    }

    $product = StoreProductInstant::whereCode($selectedCode)->first();
    if (!$product) {
      return "Produk tidak ditemukan di database.";
    }

    // Store the selected product
    $sessionContext['selected_product'] = [
      'id' => $product->id,
      'code' => $product->code,
      'title' => $product->title,
      'price' => $product->selling_price
    ];
    // Save session
    $this->session->save();

    $additionalInfo = '';
    if ($sessionContext['selected_feature'] === 'game') {
      if ($sessionContext['brands'][$sessionContext['selected_index']] === 'Mobile Legends') {
        $additionalInfo .= "Ketik *pay [zone id + id akun]* untuk melanjutkan.\n";
      } else {
        $additionalInfo .= "Ketik *pay [id_game]* untuk melanjutkan.\n";
      }
      $additionalInfo .= "Contoh: *pay 1234567890*.";
    } else {
      $additionalInfo .= "Ketik *pay [ref]* untuk memilih pembayaran.";
    }
    // ✦
    return "╰──╮ DETAIL PRODUCT ╭──╯\n\n" .
      "Kode: *" . $product->code . "*\n" .
      "Produk: " . $product->title . "\n" .
      "Kategori: " . $product->category . "\n" .
      "Provider: " . $product->brand . "\n" .
      "Harga: *Rp " . number_format($product->selling_price, 0, ',', '.') . "*\n" .
      ($product->highlight ? "╰┈➤" . $product->highlight . "" : "") .
      "\n\n$additionalInfo";
  }

  /**
   * Get available payment channels
   */
  public function paymentChannels()
  {
    // Fetch payment channels from Tripay
    try {
      $product = \App\Models\StoreProductInstant::whereStore_id($this->store->id)
        ->whereCode($this->session->context_data['code'] ?? null)
        ->first();
      $channels = tripay()->getPaymentChannels()['data'] ?? [];
      $channels = array_filter($channels, function ($channel) use ($product) {
        \Log::debug('Checking payment channel', [
          'channel' => $channel
        ]);
        return ($channel['code'] === 'QRIS2') || ($channel['group'] === '"Virtual Account"' && $product->selling_price > 10000);
      });
      $channels = array_values($channels); // Re-index array
      if (empty($channels)) {
        return "Tidak ada metode pembayaran tersedia saat ini.";
      }
      $sessionContext = $this->session->context_data ?? [];
      $response = "💳 *Silakan pilih metode pembayaran:*\n\n";
      foreach ($channels as $index => $channel) {
        $response .= ($index + 1) . ". " . $channel['name'] . " (" . $channel['code'] . ")\n";

        // Store payment channels in session
        if (!isset($this->session->context_data['payment_channels'])) {
          $sessionContext['payment_channels'] = [];
        }
        $sessionContext['payment_channels'][$index + 1] = [
          'name' => $channel['name'],
          'code' => $channel['code']
        ];
      }
      $this->session->context_data = $sessionContext;
      // Save session
      $this->session->save();

      return $response . "\nKetik *checkout [number]* untuk memilih metode pembayaran.\nContoh pembayaran dengan " . $sessionContext['payment_channels'][sizeof($channels)]['name'] . " ketik *checkout " . sizeof($channels) . "*";
    } catch (\Exception $e) {
      Log::error('Error fetching payment channels: ' . $e->getMessage());
      return "Terjadi kesalahan saat mengambil metode pembayaran.";
    }
  }

  /**
   * Get checkout information
   */
  public function checkoutInfo()
  {
    $contextData = $this->session->context_data ?? [];
    $product = \App\Models\StoreProductInstant::whereStore_id($this->store->id)->whereCode($context_data['code'])->first();
    $paymentMethod = $this->session->context_data['channels'][$contextData['selected_channel_index']];
    $customerRef = $this->session->context_data['ref'];

    // Calculate total with payment fee
    $fee = $paymentMethod['fee'] ?? 0;
    $total = $product['price'] + $fee;

    // Prepare context data for transaction
    $contextData['transaction_payload'] = [
      'product_id' => $product->id,
      'channel_code' => $paymentMethod['code'],
      'ref' => $customerRef
    ];

    // Save session
    $this->session->save();

    return "Detail Pembayaran:\n\n" .
      "Produk: " . $product->title . "\n" .
      "Nomor Tujuan: " . $customerRef . "\n" .
      "Metode Pembayaran: " . $paymentMethod['name'] . "\n" .
      "「 Harga: Rp" . number_format($product->seller_price, 0, ',', '.') . " 」\n" .
      "「 Biaya Admin: Rp" . number_format($fee, 0, ',', '.') . " 」\n" .
      "「 Total: Rp" . number_format($product->seller_price, 0, ',', '.') . " 」\n\n" .
      "Konfirmasi pembayaran Ketik *y* untuk melanjutkan pembayaran.";
  }

  /**
   * Process payment
   */
  public function processPayment()
  {
    $contextData = $this->session->context_data ?? [];
    $product = \App\Models\StoreProductInstant::whereStore_id($this->store->id)->whereCode($contextData['code'])->first();
    $paymentMethod = $this->session->context_data['payment_channels'][$contextData['selected_channel_index']];
    $customerRef = $this->session->context_data['ref'];

    try {
      \DB::beginTransaction();
      // Create WhatsApp transaction record
      $whatsappCustomer = \App\Models\WhatsappCustomer::firstOrCreate(
        ['phone_number' => $this->session->phone_number, 'store_id' => $this->store->id],
        ['name' => $this->session->customer_name ?? 'Customer ' . $this->session->id]
      );

      $orderNumber = 'WA-' . time() . '-' . rand(1000, 9999);
      $total = $product->selling_price;

      Log::info('Creating payment record');
      // Create payment record
      $payment = \App\Models\Payment::create([
        'status' => 'pending',
        'amount' => $product->selling_price
      ]);
      Log::info('Creating transaction record');
      $transaction = TransactionWhatsapp::create([
        'store_id' => $this->store->id,
        'whatsapp_customer_id' => $whatsappCustomer->id,
        'order_number' => $orderNumber,
        'status' => 'pending',
        'payment_status' => 'pending',
        'payment_method' => $paymentMethod['code'],
        'total' => $product->selling_price,
        'notes' => 'PPOB Purchase: ' . $product->title . ' for ' . $contextData['ref'],
        'source' => 'whatsapp',
        'payment_id' => $payment->id,
        'meta_data' => [
          'product_id' => $product['id'],
          'product_code' => $product['code'],
          'ref' => $contextData['ref'],
          'product_title' => $product->title,
          'product_price' => $product->selling_price,
          'total' => $product->selling_price
        ]
      ]);

      Log::info('Requesting transaction tripay');
      $expired = now()->addSeconds(3600);
      // Create transaction in Tripay
      $paymentRequest = tripay()->createTransaction([
        'method' => $paymentMethod['code'],
        'merchant_ref' => $orderNumber,
        'amount' => $product->selling_price,
        'customer_name' => $whatsappCustomer->name,
        'customer_email' => $whatsappCustomer->email ?? $this->store->user->email,
        'customer_phone' => $whatsappCustomer->phone_number,
        'order_items' => [
          [
            'name' => $product->title,
            'price' => $product->selling_price,
            'quantity' => 1,
            'product_url' => url('/product/' . $product->id),
            'subtotal' => $product->selling_price
          ]
        ],
        'callback_url' => url('/api/webhook/tripay'),
        'return_url' => url('/transaction/' . $transaction->id),
        'expired_time' => $expired->timestamp, // 1 hours in seconds
        'signature' => hash_hmac('sha256', $this->store->id . $orderNumber . $total, tripay()->getSecretKey())
      ]);
      Log::debug('Tripay payment request', [
        'response' => $paymentRequest
      ]);
      if (!$paymentRequest['status']) {
        throw new \Exception('Gagal membuat transaksi pembayaran.');
      }

      Log::info('Update payment data');
      // Update payment with Tripay reference
      $payment->update([
        'expired_at' => $expired,
        'data' => json_encode(array_merge(
          json_decode($payment->data ?? '[]', true),
          ['tripay_reference' => $paymentRequest['data']]
        ))
      ]);

      Log::info('Update session transaction data');
      // Store payment info in session
      $contextData['transaction_id'] = $transaction->id;
      $contextData['payment_id'] = $payment->id;

      $this->session->context_data = $contextData;
      $this->session->current_state = 'waiting_payment';
      // Save session
      $this->session->save();

      // Return payment information
      $responseInfo = "✮ Invoice Pembayaran $orderNumber ✮\n\n" .
        "Pesanan: " . $product->title . "\n" .
        "Total: Rp " . number_format($total, 0, ',', '.') . "\n" .
        "Metode: " . $paymentMethod['name'] . "\n" .
        "Tanggal Kedaluarsa: " . $payment->expired_at->format('l, d F Y') . " Jam " . $payment->expired_at->format('H:i:s') . "\n\n" .
        "Ketik *status* untuk mengecek status pembayaran.";
      app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($this->store->id, $this->session->phone_number, $responseInfo, $this->session);

      $responsePayment = "Harap selesaikan pembayaran sebelum " . $payment->expired_at->format('d M Y H:i');
      if ($paymentMethod['code'] === 'QRIS2') {
        app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($this->store->id, $this->session->phone_number, $responsePayment, $this->session, $paymentRequest['data']['data']['qr_url'], 'image');
      } else {
        $responsePayment .= "\n\n" .
          "\n\nBank *" . $paymentMethod['data']['data']['payment_name'] . "*" .
          "\n→ Virtual Account: *" . $paymentRequest['data']['data']['pay_code'] . "*";
        app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($this->store->id, $this->session->phone_number, $responsePayment, $this->session);
      }
      \DB::commit();
      return null;
    } catch (\Exception $e) {
      \DB::rollBack();
      Log::error('Error processing payment: ' . $e->getMessage());
      return "Terjadi kesalahan saat memproses pembayaran: " . $e->getMessage();
    }
  }

  /**
   * Improved transaction status check that handles both direct checking and webhook updates
   */
  public function transactionStatus()
  {
    $transactionId = $this->session->context_data['transaction_id'];
    $paymentId = $this->session->context_data['payment_id'];

    // Get transaction and payment from database
    $transaction = TransactionWhatsapp::find($transactionId);
    $payment = Payment::find($paymentId);

    if (!$transaction || !$payment) {
      return "Transaksi tidak ditemukan.";
    }

    // First check if payment data has been updated by webhook
    // This will refresh payment data in case webhook has updated it
    if ($payment->status === 'pending') {
      // For pending payments, check with payment gateway if there's an update
      // This is optional and can be implemented to actively check status
      // For now, we'll just rely on webhook updates
      Log::info('Checking pending payment status for WhatsApp transaction', [
        'payment_id' => $payment->id,
        'transaction_id' => $transaction->id,
        'status' => $payment->status
      ]);
    }

    // Check payment status
    if ($payment->status == 'pending') {
      // Still pending, show payment instructions
      $response = "⏳ *Menunggu Pembayaran*\n\n" .
        "No. Pesanan: " . $transaction->order_number . "\n" .
        "Status: Menunggu Pembayaran\n" .
        "Total: Rp " . number_format($transaction->total, 0, ',', '.') . "\n" .
        "Metode: " . $transaction->payment_method . "\n\n";

      if (isset($this->session->context_data['payment_url'])) {
        $response .= "Link Pembayaran:\n" . $this->session->context_data['payment_url'] . "\n\n";
      }

      $response .= "Harap selesaikan pembayaran sebelum " . \Carbon\Carbon::parse($payment->expired_at)->format('d M Y H:i') . "\n\n" .
        "Ketik *status* untuk mengecek kembali status pembayaran.";

      return $response;
    } else if ($payment->status == 'settlement') {
      // Payment successful
      $product = StoreProductInstant::find($transaction->meta_data['product_id'] ?? null);
      $productTitle = $product ? $product->title : $transaction->meta_data['product_title'] ?? 'Produk';

      $response = "✅ *Pembayaran Berhasil*\n\n" .
        "No. Pesanan: " . $transaction->order_number . "\n" .
        "Status: Pembayaran Berhasil\n" .
        "Produk: " . $productTitle . "\n" .
        "Total: Rp " . number_format($transaction->total, 0, ',', '.') . "\n" .
        "Tanggal: " . $payment->settlement_at->format('d M Y H:i') . "\n\n" .
        "Terima kasih telah berbelanja di " . $this->store->name . "!";

      // Clear transaction from session and reset state
      $this->session->context_data = [];
      $this->session->current_state = null;
      $this->session->save();

      return $response;
    } else if ($payment->status == 'expired') {
      // Payment expired
      $response = "❌ *Pembayaran Kedaluwarsa*\n\n" .
        "No. Pesanan: " . $transaction->order_number . "\n" .
        "Status: Pembayaran Kedaluwarsa\n" .
        "Total: Rp " . number_format($transaction->total, 0, ',', '.') . "\n\n" .
        "Pembayaran Anda telah kedaluwarsa. Silakan lakukan pembelian baru.";

      // Clear transaction from session and reset state
      $this->session->context_data = [];
      $this->session->current_state = null;
      $this->session->save();

      return $response;
    } else {
      // Failed or other status
      $response = "❌ *Pembayaran Gagal*\n\n" .
        "No. Pesanan: " . $transaction->order_number . "\n" .
        "Status: " . ucfirst($payment->status) . "\n" .
        "Total: Rp " . number_format($transaction->total, 0, ',', '.') . "\n\n" .
        "Terjadi kesalahan pada pembayaran Anda. Silakan lakukan pembelian baru.";

      // Clear transaction from session and reset state
      unset($this->session->context_data['transaction_id']);
      unset($this->session->context_data['payment_id']);
      unset($this->session->context_data['payment_url']);
      unset($this->session->context_data['payment_instructions']);
      $this->session->current_state = null;
      $this->session->save();

      return $response;
    }
  }
}
