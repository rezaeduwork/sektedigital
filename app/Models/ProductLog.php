<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
  use HasFactory;
  /**
   * activity: 'create','update','view'
   */
  protected $fillable = [
    'activity',
    'description',
    'by',
    'product_id'
  ];
  public function product()
  {
    return $this->belongsTo(\App\Models\Product::class, 'product_id');
  }
}
