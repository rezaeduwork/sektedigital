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
        ->where('status', '=', 'pending')
        ->first();

      if (! $invoice) {
        return response()->json([
          'success' => false,
          'message' => 'No invoice found or already paid: ' . $invoiceId,
        ]);
      }

      switch ($status) {
        case 'PAID':
          // DOUBLE CHECK TX
          $checkTx = tripay()->checkTransactionDetail($invoice->data['reference']);
          if ($checkTx['status'] === true) {
            $invoice->update(['status' => 'settlement', 'settlement_at' => now()]);

            $invoice->transactions()->update(['status' => 'confirmed']);
            foreach ($invoice->transactions as $tx) {
              transactionActivity($tx, $tx->user_id, 'confirmed', ('transaction confirmed'));
            }

            \App\Jobs\TransactionConfirmedCancellation::dispatch($invoice->id)->delay(now()->addDays(3));
            // \App\Jobs\TransactionConfirmedCancellation::dispatch($invoice->id);

            $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'settlement'));
          } else {
            return response()->json([
              'success' => false,
              'message' => $checkTx['data'],
            ]);
          }

          break;

        case 'EXPIRED':
          $invoice->update(['status' => 'expired']);
          $invoice->transactions()->update(['status' => 'expired']);
          foreach ($invoice->transactions as $tx) {
            transactionActivity($tx, $tx->user_id, 'expired', ('transaction expired'));
          }
          break;

          $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'expired'));
        case 'FAILED':
          $invoice->update(['status' => 'rejected']);
          $invoice->transactions()->update(['status' => 'rejected']);
          foreach ($invoice->transactions as $tx) {
            transactionActivity($tx, $tx->user_id, 'rejected', ('transaction rejected'));
          }
          break;

          $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'rejected'));
        default:
          return response()->json([
            'success' => false,
            'message' => 'Unrecognized payment status',
          ]);
      }

      return response()->json(['success' => true]);
    }
  }
}
