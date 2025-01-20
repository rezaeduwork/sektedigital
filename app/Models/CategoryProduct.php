<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'description',
    'icon'
  ];
  public function products()
  {
    return $this->hasMany('App\Models\Product', 'category_product_id');
  }
}
