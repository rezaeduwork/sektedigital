<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  use HasFactory;
  // comment('unprocessed | confirmed | accepted | processed | finished | rejected | cancelled | inspection')
  protected $fillable = [
    'status','amount','customer_name','customer_email','customer_phone'
  ];
  public function detail() {
    return $this->hasMany('App\Livewire\TransactionDetail', 'transaction_id');
  }
  public function logs() {
    return $this->hasMany('App\Livewire\TransactionLog', 'transaction_id');
  }
}
