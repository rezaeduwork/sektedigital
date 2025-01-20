<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
  use HasFactory;
  protected $fillable = [
    'transaction_id',
    'product_id',
    'price',
    'quantity',
    'subtotal',
    'note',
    // comment('unprocessed | confirmed | accepted | processed | store_finished | finished | rejected | cancelled | inspection')
    'status',
    'store_id'
  ];
  public function product()
  {
    return $this->belongsTo('App\Models\Product', 'product_id');
  }
  public function transaction()
  {
    return $this->belongsTo('App\Models\Transaction', 'transaction_id');
  }
}
