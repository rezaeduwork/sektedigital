<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreWhatsappSetting extends Model
{
  use HasFactory;

  protected $fillable = [
    'store_id',
    'is_bot_active',
    'send_welcome_message',
    'welcome_message',
  ];

  protected $casts = [
    'is_bot_active' => 'boolean',
    'send_welcome_message' => 'boolean',
  ];

  /**
   * Get the store that owns the WhatsApp settings.
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Get the default welcome message for a store.
   *
   * @return string
   */
  public static function getDefaultWelcomeMessage()
  {
    return "🤖 Hello! I'm your store assistant bot.\n\n"
      . "I'm here to help you with your inquiries. You can ask me about:\n"
      . "- Product information\n"
      . "- Store hours\n"
      . "- Order status\n"
      . "- And more!\n\n"
      . "Just type your question and I'll do my best to assist you!";
  }
}
