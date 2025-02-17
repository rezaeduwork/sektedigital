<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    // comment('unverified|verified|inactive')
    'status',
    'user_id',
    'photo'
  ];
  public function products()
  {
    return $this->hasMany('App\Models\Product', 'store_id');
  }
  public function user()
  {
    return $this->belongsTo('App\Models\User', 'user_id');
  }
}
