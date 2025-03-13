<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSingle extends Model
{
  use HasFactory;
  protected $fillable = [
    // comment('unprocessed | confirmed | processed | finished | rejected | cancelled | inspection')
    'status',
    'amount',
    'customer_name',
    'product_name',
    'customer_email',
    'customer_phone',
    'user_id',
    'payment_id',
  ];
}
