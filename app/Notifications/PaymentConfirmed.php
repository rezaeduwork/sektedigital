<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmed extends Notification
{
  use Queueable;

  public $payment;
  public $status;
  /**
   * Create a new notification instance.
   */
  public function __construct(\App\Models\Payment $payment, $status)
  {
    $this->payment = $payment;
    $this->status = $status;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['database'];
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
    if ($this->status == 'expired') {
      return [
        'id' => $this->payment->id,
        'title' => 'Pembayaran Kedaluarsa.',
        'description' => 'Pesanan sebesar ' . number_format($this->payment->amount, 0, ',', '.') . ' telah kedaluarsa.',
        'url' => url('payment/' . $this->payment->id . '/detail'),
      ];
    } else if ($this->status == 'rejected') {
      return [
        'id' => $this->payment->id,
        'title' => 'Pembayaran Gagal.',
        'description' => 'Pesanan sebesar ' . number_format($this->payment->amount, 0, ',', '.') . ' ditolak 😢',
        'url' => url('payment/' . $this->payment->id . '/detail'),
      ];
    }

    return [
      'id' => $this->payment->id,
      'title' => 'Pembayaran Berhasil.',
      'description' => 'Pesanan sebesar ' . number_format($this->payment->amount, 0, ',', '.') . ' telah dikonfirmasi berhasil 😊, Silahkan hubungi seller untuk segera memproses pesananmu.',
      'url' => url('payment/' . $this->payment->id . '/detail'),
    ];
  }
}
