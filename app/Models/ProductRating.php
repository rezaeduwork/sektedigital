<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
  use HasFactory;
  protected $fillable = [
    'rating',
    'feedback',
    'product_id',
    'transaction_id',
    'user_id'
  ];
  public function product()
  {
    return $this->belongsTo(\App\Models\Product::class);
  }

  public function transaction()
  {
    return $this->belongsTo(\App\Models\Transaction::class);
  }

  public function user()
  {
    return $this->belongsTo(\App\Models\User::class);
  }
}
