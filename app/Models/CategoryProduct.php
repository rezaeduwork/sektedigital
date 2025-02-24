<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryProduct extends Model
{
  use HasFactory, SoftDeletes;
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
