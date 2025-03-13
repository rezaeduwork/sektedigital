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
    'quantity',
    'data'
  ];
  public function product()
  {
    $data = $this->data;
    if ($data) {
      return collect(config('product'))->where('code', $data->product_code)->first();
    }
    return null;
  }
  public function getDataAttribute($value)
  {
    if ($value) {
      return json_decode($value, true);
    }
    return null;
  }
}
