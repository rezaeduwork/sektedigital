<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreWhatsapp extends Model
{
  use HasFactory;

  protected $fillable = [
    'store_id',
    'phone_number',
    'status',
    'qr_code',
    'session_data',
    'last_connected_at',
  ];

  protected $casts = [
    'session_data' => 'array',
    'last_connected_at' => 'datetime',
  ];

  /**
   * Get the store that owns this WhatsApp connection.
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Check if the WhatsApp is connected.
   *
   * @return bool
   */
  public function isConnected()
  {
    return $this->status === 'connected';
  }
}
