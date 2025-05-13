<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionWhatsappItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'transaction_whatsapp_id',
    'product_id',
    'quantity',
    'price',
    'total',
    'options',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'options' => 'array',
    'price' => 'float',
    'total' => 'float',
  ];

  /**
   * Get the order that owns the item.
   */
  public function order()
  {
    return $this->belongsTo(TransactionWhatsapp::class, 'transaction_whatsapp_id');
  }

  /**
   * Get the product for this item.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Calculate the total based on price and quantity.
   */
  public function calculateTotal()
  {
    $this->total = $this->price * $this->quantity;
    $this->save();

    return $this->total;
  }
}
