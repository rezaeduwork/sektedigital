<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappCustomerSession extends Model
{
  use HasFactory;

  protected $fillable = [
    'store_id',
    'phone_number',
    'session_id',
    'customer_name',
    'cart_data',
    'current_state',
    'context_data',
    'last_interaction_at',
    'is_active'
  ];

  protected $casts = [
    'cart_data' => 'array',
    'context_data' => 'array',
    'last_interaction_at' => 'datetime',
    'is_active' => 'boolean',
  ];

  /**
   * Get the store that owns the session.
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Get the messages associated with this session.
   */
  public function messages()
  {
    return $this->hasMany(WhatsappBotMessage::class, 'session_id', 'session_id');
  }

  /**
   * Update the last interaction time.
   *
   * @param  string|null  $attribute
   * @return bool
   */
  public function touch($attribute = null)
  {
    $this->last_interaction_at = now();
    $this->save();

    return parent::touch($attribute);
  }

  /**
   * Scope a query to only include active sessions.
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * Get cart items count.
   */
  public function getCartItemsCountAttribute()
  {
    if (!$this->cart_data || !isset($this->cart_data['items'])) {
      return 0;
    }

    return count($this->cart_data['items']);
  }
}
