<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    $tripayReference = $data->reference;
    $status = strtoupper((string) $data->status);

    if ($data->is_closed_payment === 1) {
      $invoice = \App\Models\Payment::whereId($invoiceId)
        ->when(config('app.env') === 'production', function ($query) {
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
          successPayment($invoice);
          break;

        case 'EXPIRED':
          expirePayment($invoice);
          break;
        case 'FAILED':
          if ($invoice->transaction_type == 'basic') {
            $invoice->transactions()->update(['status' => 'rejected']);
            foreach ($invoice->transactions as $tx) {
              transactionActivity($tx, $tx->user_id, 'rejected', ('transaction rejected'));
            }
          } else {
            if ($invoice->singleTransaction->product->provider == 'digiflazz') {
              $invoice->singleTransaction()->update(['status' => 'rejected']);
            }
          }
          $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'rejected'));
          break;
        default:
          return response()->json([
            'success' => false,
            'message' => 'Unrecognized payment status',
          ]);
      }

      return response()->json(['success' => true]);
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
      } else {
        if ($tx) {
          $tx->update(['status' => 'rejected']);
        }
      }
      return response()->json(['success' => true]);
    } else {
      return response()->json(['success' => false, 'message' => 'Invalid signature'], 401);
    }
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
}
