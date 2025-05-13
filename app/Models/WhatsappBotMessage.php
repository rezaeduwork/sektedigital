<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappBotMessage extends Model
{
  use HasFactory;

  protected $fillable = [
    'store_id',
    'phone_number',
    'direction',
    'message',
    'message_type',
    'media_data',
    'is_processed',
    'session_id',
    'context'
  ];

  protected $casts = [
    'media_data' => 'array',
    'is_processed' => 'boolean',
  ];

  /**
   * Get the store that owns the message.
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Get the customer session associated with this message.
   */
  public function customerSession()
  {
    return $this->belongsTo(WhatsappCustomerSession::class, 'session_id', 'session_id');
  }

  /**
   * Scope a query to only include incoming messages.
   */
  public function scopeIncoming($query)
  {
    return $query->where('direction', 'incoming');
  }

  /**
   * Scope a query to only include outgoing messages.
   */
  public function scopeOutgoing($query)
  {
    return $query->where('direction', 'outgoing');
  }
}
