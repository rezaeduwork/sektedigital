<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
  use HasFactory;
  protected $fillable = [
    'uid',
    // fund | store_fund | coin | withdraw
    'type',
    'amount',
    'description',
    // pending | success
    'status',
    'token',
    'name',
    'user_id',
    'data'
  ];
}
