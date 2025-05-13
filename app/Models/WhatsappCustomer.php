<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappCustomer extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'store_id',
    'name',
    'phone_number',
    'email',
    'address',
    'meta_data',
    'last_interaction_at',
    'is_active',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'meta_data' => 'array',
    'last_interaction_at' => 'datetime',
    'is_active' => 'boolean',
  ];

  /**
   * Get the store that owns the customer.
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Get the orders for the customer.
   */
  public function orders()
  {
    return $this->hasMany(TransactionWhatsapp::class, 'whatsapp_customer_id');
  }

  /**
   * Get the WhatsApp sessions for this customer.
   */
  public function whatsappSessions()
  {
    return $this->hasMany(WhatsappCustomerSession::class, 'phone_number', 'phone_number')
      ->where('store_id', $this->store_id);
  }
}
