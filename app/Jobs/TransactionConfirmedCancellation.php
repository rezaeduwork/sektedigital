<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransactionConfirmedCancellation implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $payment;
  /**
   * Create a new job instance.
   */
  public function __construct($payment_id)
  {
    $this->payment = \App\Models\Payment::find($payment_id);
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    if ($this->payment) {
      if ($this->payment->status == 'settlement') {
        foreach ($this->payment->transactions as $row) {
          if ($row->status === 'confirmed') {
            $row->status = 'cancelled';
            $row->save();
            transactionActivity($row, $row->user_id, 'cancelled', 'Dibatalkan system pada ' . now()->translatedFormat('l, d F Y H:i:s'), null, null);
            \App\Models\UserBalance::create([
              'user_id' => $this->payment->user->id,
              'name' => 'Refund payment',
              'type' => 'fund',
              'status' => 'success',
              'amount' => $row->amount,
              'description' => 'Refund payment untuk transaksi #' . $row->id,
              'uid' => $row->user_id . uniqid() . time()
            ]);
          }
        }
        $this->payment->user->reloadBalance();
      }
    }
  }
}
