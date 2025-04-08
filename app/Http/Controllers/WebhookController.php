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
          // DOUBLE CHECK TX
          $checkTx = tripay()->checkTransactionDetail($invoice->data['reference']);
          if ($checkTx['status'] === true) {
            $invoice->update(['status' => 'settlement', 'settlement_at' => now()]);

            if ($invoice->transaction_type == 'basic') {
              $invoice->transactions()->where('status', 'unprocessed')->update(['status' => 'confirmed']);
              foreach ($invoice->transactions()->where('status', 'confirmed')->get() as $tx) {
                transactionActivity($tx, $tx->user_id, 'confirmed', ('transaction confirmed'));
              }
              // \App\Jobs\TransactionConfirmedCancellation::dispatch($invoice->id)->delay(now()->addDays(3));
            } else {
              if ($invoice->singleTransaction->product->provider == 'digiflazz') {
                $data = digiflazz()->createTransaction($invoice->singleTransaction);
                if ($data['success'] === true) {
                  $invoice->singleTransaction()->update(['status' => 'finished']);
                } else {
                  if ($data['data']['status'] == 'Pending') {
                    $invoice->singleTransaction()->update(['status' => 'confirmed']);
                  } else {
                    $invoice->singleTransaction()->update(['status' => 'rejected']);
                  }
                }
              }
            }

            if ($invoice->user) {
              $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'settlement'));
            }
          } else {
            return response()->json([
              'success' => false,
              'message' => $checkTx['data'],
            ]);
          }

          break;

        case 'EXPIRED':
          $invoice->update(['status' => 'expired']);
          if ($invoice->singleTransaction) {
          } else {
            $invoice->transactions()->update(['status' => 'expired']);
            foreach ($invoice->transactions as $tx) {
              transactionActivity($tx, $tx->user_id, 'expired', ('transaction expired'));
            }
          }
          break;

          $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'expired'));
        case 'FAILED':
          $invoice->update(['status' => 'rejected']);
          if ($invoice->singleTransaction) {
          } else {
            $invoice->transactions()->update(['status' => 'rejected']);
            foreach ($invoice->transactions as $tx) {
              transactionActivity($tx, $tx->user_id, 'rejected', ('transaction rejected'));
            }
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
}
