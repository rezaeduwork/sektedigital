<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionWhatsapp extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'store_id',
    'whatsapp_customer_id',
    'order_number',
    'status',
    'payment_status',
    'payment_method',
    'payment_id',
    'total',
    'notes',
    'shipping_address',
    'source',
    'meta_data',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'meta_data' => 'array',
    'total' => 'float',
  ];

  /**
   * Get the store that owns the order.
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Get the customer that owns the order.
   */
  public function customer()
  {
    return $this->belongsTo(WhatsappCustomer::class, 'whatsapp_customer_id');
  }

  /**
   * Get the items for the order.
   */
  public function items()
  {
    return $this->hasMany(TransactionWhatsappItem::class);
  }

  /**
   * Calculate the total from the items.
   */
  public function calculateTotal()
  {
    $this->total = $this->items->sum('total');
    $this->save();

    return $this->total;
  }
}
