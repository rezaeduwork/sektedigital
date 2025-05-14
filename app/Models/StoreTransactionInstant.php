<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreTransactionInstant extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'code',
    'store_id',
    'store_product_instant_id',
    'provider',
    'customer_no',
    'price',
    'fee',
    'total',
    'status',
    'data',
    'response_data',
    'processed_at',
    'completed_at'
  ];

  protected $casts = [
    'data' => 'array',
    'response_data' => 'array',
    'processed_at' => 'datetime',
    'completed_at' => 'datetime',
  ];

  /**
   * Get the store that owns the transaction
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Get the product instant
   */
  public function storeProductInstant()
  {
    return $this->belongsTo(StoreProductInstant::class);
  }

  /**
   * Get available statuses
   */
  public static function getStatuses()
  {
    return [
      'pending' => 'Pending',
      'processing' => 'Processing',
      'success' => 'Success',
      'failed' => 'Failed',
      'refunded' => 'Refunded'
    ];
  }

  /**
   * Generate unique transaction code
   */
  public static function generateCode()
  {
    $prefix = 'PPOB-';
    $timestamp = now()->format('ymdHi');
    $random = rand(1000, 9999);
    $code = $prefix . $timestamp . $random;

    // Check if code already exists
    while (self::where('code', $code)->exists()) {
      $random = rand(1000, 9999);
      $code = $prefix . $timestamp . $random;
    }

    return $code;
  }
}
