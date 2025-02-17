<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionNotification extends Notification
{
  use Queueable;
  public $tx;
  public $title;
  /**
   * Create a new notification instance.
   */
  public function __construct(\App\Models\Transaction $tx, $title, $description)
  {
    $this->tx = $tx;
    $this->title = $title;
    $this->description = $description;
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
    return [
      'id' => $this->tx->id,
      'title' => $this->title,
      'description' => $this->description,
      'url' => url('/user/transaction?tab=' . $this->tx->status),
    ];
  }
}
