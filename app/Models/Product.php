<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;
  protected $fillable = [
    'title',
    'description',
    'price',
    'store_id',
    'category_product_id',
    'slug',
    'stock',
    'status'
  ];
  public function category() {
    return $this->belongsTo('App\Models\CategoryProduct', 'category_product_id');
  }
  public function store() {
    return $this->belongsTo('App\Models\Store', 'store_id');
  }
  public function images() {
    return $this->hasMany('App\Models\ProductImage', 'product_id');
  }
  public function logs() {
    return $this->hasMany('App\Models\ProductLog', 'product_id');
  }
  public function views() {
    return $this->logs()->where('activity', 'view')->count();
  }
  public function scopeAvailable($query) {
    return $query->whereStatus('active')->where('stock', '>', 0);
  }
}
