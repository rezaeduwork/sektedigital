<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\ProductInstant;
use App\Models\StoreProductInstant;
use App\Models\Store;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
  public function tripayNotification(Request $request)
  {
    $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
    $json = $request->getContent();
    $signatureformatted = hash_hmac('sha256', $json, tripay()->getSecretKey());

    if ($request->server('HTTP_X_CALLBACK_SIGNATURE') != $signatureformatted) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid signature',
      ]);
    }

    if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
      return response()->json([
        'success' => false,
        'message' => 'Unrecognized callback event, no action was taken',
      ]);
    }

    $data = json_decode($json);

    if (JSON_ERROR_NONE !== json_last_error()) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid data sent by tripay',
      ]);
    }

    $invoiceId = $data->merchant_ref;
    $invoiceId = str_replace('DEPOSIT-', '', $invoiceId);
    $tripayReference = $data->reference;
    $status = strtoupper((string) $data->status);
    if ($data->is_closed_payment === 1) {
      $invoice = Payment::whereId($invoiceId)
        ->when(true, function ($query) {
          $query->where('status', '=', 'pending');
        })
        ->first();

      if (! $invoice) {
        return response()->json([
          'success' => false,
          'message' => 'No invoice found or already paid: ' . $invoiceId,
        ]);
      }

      switch ($status) {
        case 'PAID':
          $result = $this->processSuccessfulPayment($invoice);
          return response()->json(['success' => $result['success'], 'message' => $result['message'] ?? null]);

        case 'EXPIRED':
          $this->processExpiredPayment($invoice);
          return response()->json(['success' => true]);

        case 'FAILED':
          $this->processFailedPayment($invoice);
          return response()->json(['success' => true]);

        default:
          return response()->json([
            'success' => false,
            'message' => 'Unrecognized payment status',
          ]);
      }
    }

    return response()->json(['success' => false, 'message' => 'Not a closed payment']);
  }

  /**
   * Process a successful payment
   *
   * @param \App\Models\Payment $invoice
   * @return array
   */
  protected function processSuccessfulPayment($invoice)
  {
    // Update payment status
    $invoice->update([
      'status' => 'settlement',
      'settlement_at' => now()
    ]);

    try {
      if ($invoice->transaction_type == 'deposit') {
        // Handle deposit transactions
        $this->processDepositPayment($invoice);
      } else if ($invoice->transaction_type == 'basic') {
        // Handle regular transactions
        $invoice->transactions()->where('status', 'unprocessed')->update(['status' => 'confirmed']);
        foreach ($invoice->transactions()->where('status', 'confirmed')->get() as $tx) {
          transactionActivity($tx, $tx->user_id, 'confirmed', 'transaction confirmed');
        }
      } else if ($invoice->transaction_type == 'seller_ppob') {
        // Handle PPOB stock purchase for sellers
        $paymentData = $invoice->data;
        $this->processPpobStockPurchase($invoice, $paymentData);
      } else if ($invoice->transaction_type == 'instant') {
        // Handle PPOB product payment
        $paymentData = json_decode($invoice->data, true);

        // Check if this is a PPOB stock purchase
        if (
          isset($paymentData['transaction_data']['data']['merchant_ref']) &&
          strpos($paymentData['transaction_data']['data']['merchant_ref'], 'PPOB-STOCK-') === 0
        ) {

          $this->processPpobStockPurchase($invoice, $paymentData);
        } else if ($invoice->singleTransaction && $invoice->singleTransaction->product->provider == 'digiflazz') {
          // Handle regular instant transactions
          $data = digiflazz()->createTransaction($invoice->singleTransaction);
          if ($data['success'] === true) {
            $invoice->singleTransaction()->where('status', 'unprocessed')->update(['status' => 'finished']);
          } else {
            $status = isset($data['data']['status']) ? $data['data']['status'] : null;
            if ($status == 'Pending') {
              $invoice->singleTransaction()->where('status', 'unprocessed')->update(['status' => 'confirmed']);
            } else {
              $invoice->singleTransaction()->where('status', 'unprocessed')->update(['status' => 'rejected']);
            }
          }
        }
      }

      // Send notification
      if ($invoice->user) {
        $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'settlement'));
      }

      return ['success' => true];
    } catch (\Exception $e) {
      Log::error('Payment processing error: ' . $e->getMessage());
      return [
        'success' => false,
        'message' => 'Error processing payment: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Process an expired payment
   *
   * @param \App\Models\Payment $invoice
   */
  protected function processExpiredPayment($invoice)
  {
    if ($invoice->status !== 'pending') {
      return;
    }

    $invoice->update(['status' => 'expired']);

    if ($invoice->transaction_type == 'basic') {
      $invoice->transactions()->where('status', 'unprocessed')->update(['status' => 'expired']);
      foreach ($invoice->transactions as $tx) {
        transactionActivity($tx, $tx->user_id, 'expired', 'transaction expired');
      }
    } else {
      if ($invoice->singleTransaction && $invoice->singleTransaction->product->provider == 'digiflazz') {
        $invoice->singleTransaction()->where('status', 'unprocessed')->update(['status' => 'expired']);
      }
    }

    if ($invoice->user) {
      $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'expired'));
    }
  }

  /**
   * Process a failed payment
   *
   * @param \App\Models\Payment $invoice
   */
  protected function processFailedPayment($invoice)
  {
    $invoice->update(['status' => 'failed']);

    if ($invoice->transaction_type == 'basic') {
      $invoice->transactions()->update(['status' => 'rejected']);
      foreach ($invoice->transactions as $tx) {
        transactionActivity($tx, $tx->user_id, 'rejected', 'transaction rejected');
      }
    } else {
      if ($invoice->singleTransaction && $invoice->singleTransaction->product->provider == 'digiflazz') {
        $invoice->singleTransaction()->update(['status' => 'rejected']);
      }
    }

    if ($invoice->user) {
      $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'rejected'));
    }
  }

  /**
   * Process PPOB stock purchase
   *
   * @param \App\Models\Payment $invoice
   * @param array $paymentData
   */
  protected function processPpobStockPurchase($invoice, $paymentData)
  {
    $store = Store::where('user_id', $invoice->user_id)->first();
    if (!$store) {
      Log::error('PPOB stock purchase failed - store not found for user ID: ' . $invoice->user_id);
      return false;
    }

    $productId = $paymentData['product_id'] ?? null;
    $quantity = $paymentData['quantity'] ?? 1;
    $sellingPrice = $paymentData['selling_price'] ?? null;

    if (!$productId || !$sellingPrice) {
      Log::error('PPOB stock purchase failed - missing product data: ' . json_encode($paymentData));
      return false;
    }

    $product = ProductInstant::find($productId);
    if (!$product) {
      Log::error('PPOB stock purchase failed - product not found: ' . $productId);
      return false;
    }
    try {
      // Check if product already exists for this store
      $existingProduct = StoreProductInstant::where('store_id', $store->id)
        ->where('product_instant_id', $product->id)
        ->first();

      if ($existingProduct) {
        // Update existing product
        $existingProduct->update([
          'selling_price' => $sellingPrice,
          'stock' => $existingProduct->stock + $quantity,
        ]);
        Log::info('PPOB stock updated for store #' . $store->id . ', product #' . $product->id);
      } else {
        // Create new product
        $storePPOBProduct = StoreProductInstant::create([
          'store_id' => $store->id,
          'product_instant_id' => $product->id,
          'code' => $product->code,
          'provider' => $product->provider,
          'brand' => $product->brand,
          'category' => $product->category,
          'title' => $product->title,
          'highlight' => $product->highlight,
          'description' => $product->description,
          'price' => $product->price,
          'selling_price' => $sellingPrice,
          'slug' => $product->slug,
          'stock' => $quantity,
          'status' => 'active',
          'image' => $product->image,
          'type' => $product->type,
        ]);
        Log::info('New PPOB stock created for store #' . $store->id . ', product #' . $product->id);
      }
      return true;
    } catch (\Exception $e) {
      Log::error('PPOB stock purchase processing error: ' . $e->getMessage());
      return false;
    }
  }

  public function digiflazzNotification(Request $request)
  {
    $secret = digiflazz()->getSecretKey();
    $post_data = file_get_contents('php://input');
    $signature = hash_hmac('sha1', $post_data, $secret);

    if ($request->header('X-Hub-Signature') == 'sha1=' . $signature && $request->header('x-digiflazz-event') == 'update') {
      $data = json_decode($request->getContent(), true);
      $tx = \App\Models\TransactionSingle::where('id', $data['data']['ref_id'])->when(config('app.env') === 'production', function ($query) {
        $query->where('status', 'confirmed');
      })->first();
      if ($data['data']['status'] == 'Sukses') {
        if ($tx) {
          $tx->update(['status' => 'finished']);
        }
      }
      return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Unauthorized']);
  }

  /**
   * Handle WhatsApp webhook notifications from the gateway.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function whatsappWebhook(Request $request)
  {
    // Validate request
    $token = $request->bearerToken();
    $expectedToken = env('WEBHOOK_AUTH_TOKEN', 'simple_api_token_123');

    if ($token !== $expectedToken) {
      return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    // Get the payload data
    $data = $request->input('data', []);
    $type = $request->input('type');
    $formattedConnectionId = $request->input('connectionId');

    if (empty($formattedConnectionId)) {
      return response()->json(['success' => false, 'message' => 'Connection ID is required']);
    }

    // Extract the pure ID from the formatted connection ID (CID_{id})
    $connectionId = $formattedConnectionId;
    if (strpos($formattedConnectionId, 'CID_') === 0) {
      $connectionId = substr($formattedConnectionId, 4); // Remove 'CID_' prefix
    }

    // Find the store WhatsApp record using the extracted ID
    $storeWhatsapp = \App\Models\StoreWhatsapp::find($connectionId);

    if (!$storeWhatsapp) {
      return response()->json(['success' => false, 'message' => 'Unknown connection ID']);
    }

    // Handle different event types
    switch ($type) {
      case 'qr_code':
        // Update the QR code in the database
        $storeWhatsapp->update([
          'status' => 'connecting',
          'qr_code' => $data['qr'] ?? null,
        ]);
        break;

      case 'connection_update':
        // Handle connection status updates
        if (isset($data['status'])) {
          $status = $data['status'];

          if ($status === 'connected') {
            $storeWhatsapp->update([
              'status' => 'connected',
              'qr_code' => null,
              'last_connected_at' => now(),
              'phone_number' => $data['user']['id'] ?? $storeWhatsapp->phone_number,
            ]);
          } elseif ($status === 'disconnected') {
            $storeWhatsapp->update([
              'status' => 'disconnected',
              'qr_code' => null,
            ]);
          }
        }
        break;

      case 'connection_error':
        // We don't store errors in the database, frontend will handle displaying errors
        // This is just for logging purposes
        \Log::error('WhatsApp connection error for ' . $formattedConnectionId . ' (ID: ' . $connectionId . '): ' .
          ($data['error']['message'] ?? 'Unknown error'));
        break;

      case 'retry_attempt':
        // We don't need to store this info, but we can log it
        if (isset($data['status']) && $data['status'] === 'failed' && isset($data['maxRetriesReached'])) {
          \Log::error('WhatsApp connection max retry attempts reached for ' . $formattedConnectionId . ' (ID: ' . $connectionId . ')');
        }
        break;

      case 'message':
        // Handle incoming messages (can be implemented later)
        break;

      default:
        // Unknown event type
        return response()->json(['success' => false, 'message' => 'Unknown event type']);
    }

    return response()->json(['success' => true]);
  }

  /**
   * Process a deposit payment
   *
   * @param \App\Models\Payment $payment
   * @return bool
   */
  protected function processDepositPayment($payment)
  {
    if (!$payment->user) {
      Log::error('Deposit payment failed - user not found for payment ID: ' . $payment->id);
      return false;
    }

    try {
      // Create a UserBalance record for the deposit
      $payment->user->balances()->create([
        'uid' => $payment->user->id . uniqid(),
        'type' => 'store_fund',
        'amount' => $payment->amount,
        'description' => 'Deposit saldo pada ' . now()->translatedFormat('l, d F Y H:i'),
        'status' => 'success',
        'name' => 'Deposit Saldo'
      ]);

      // Reload user balance
      $payment->user->reloadBalance();

      Log::info('Deposit processed successfully for user #' . $payment->user->id . ', amount: ' . $payment->amount);
      return true;
    } catch (\Exception $e) {
      Log::error('Deposit processing error: ' . $e->getMessage());
      return false;
    }
  }
}
