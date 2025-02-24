<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use HasFactory, SoftDeletes;
  protected $fillable = [
    'title',
    'highlight',
    'description',
    'price',
    'store_id',
    'category_product_id',
    'slug',
    'stock',
    // active | inactive
    'status'
  ];
  public function category()
  {
    return $this->belongsTo('App\Models\CategoryProduct', 'category_product_id');
  }
  public function store()
  {
    return $this->belongsTo('App\Models\Store', 'store_id');
  }
  public function images()
  {
    return $this->hasMany('App\Models\ProductImage', 'product_id');
  }
  public function mainImage()
  {
    return $this->images()->whereType('main')->first();
  }
  public function logs()
  {
    return $this->hasMany('App\Models\ProductLog', 'product_id');
  }
  public function ratings()
  {
    return $this->hasMany('App\Models\ProductRating', 'product_id');
  }
  public function views()
  {
    return $this->logs()->where('activity', 'view')->count();
  }
  public function transactionDetails()
  {
    return $this->hasMany('App\Models\TransactionDetail', 'product_id');
  }

  // SCOPE
  public function scopeAvailable($query)
  {
    return $query->where('products.status', 'active')->where('products.stock', '>', 0)->when(auth()->check() && auth()->user()->store, function ($query) {
      $query->where('products.store_id', '<>', auth()->user()->store->id);
    });
  }

  // HELPERS
  public function inCartsCount()
  {
    return \App\Models\Cart::whereProduct_id($this->id)->count();
  }
  public function inTransactionFinished()
  {
    return \App\Models\TransactionDetail::whereProduct_id($this->id)->whereHas('transaction', function ($query) {
      $query->whereStatus('finished');
    })->count();
  }
}
