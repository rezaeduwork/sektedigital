<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
  use HasFactory;
  protected $fillable = [
    'name','status','user_id','photo'
  ];
  public function products() {
    return $this->hasMany('App\Models\Product', 'store_id');
  }
}
