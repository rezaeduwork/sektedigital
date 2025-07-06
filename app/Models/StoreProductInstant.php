<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProductInstant extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'store_id',
    'product_instant_id',
    'code',
    'provider',
    'brand',
    'category',
    'title',
    'highlight',
    'description',
    'price',
    'selling_price',
    'slug',
    'image',
    'type'
  ];

  /**
   * Get the store that owns the product
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Get the master product instant
   */
  public function productInstant()
  {
    return $this->belongsTo(ProductInstant::class);
  }

  /**
   * Get the transactions for this product
   */
  public function transactions()
  {
    return $this->hasMany(StoreTransactionInstant::class);
  }

  // All products are active by default now
}
