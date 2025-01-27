<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  use HasFactory;
  protected $fillable = [
    // comment('unprocessed | confirmed | accepted | processed | store_finished | finished | rejected | cancelled | inspection | complain | expired')
    'status',
    'amount',
    'customer_name',
    'customer_email',
    'customer_phone',
    'user_id',
    'payment_id',
    'store_id'
  ];
  public function user()
  {
    return $this->belongsTo('App\Models\User', 'user_id');
  }
  public function payment()
  {
    return $this->belongsTo('App\Models\Payment');
  }
  public function buyer()
  {
    return $this->hasMany('App\Models\User', 'user_id');
  }
  public function details()
  {
    return $this->hasMany('App\Models\TransactionDetail', 'transaction_id');
  }
  public function storeDetails()
  {
    return $this->hasMany('App\Models\TransactionDetail', 'transaction_id')->whereStore_id(auth()->user()->store->id ?? null);
  }
  public function stores()
  {
    return $this->hasMany('App\Models\TransactionDetail', 'transaction_id');
  }
  public function store()
  {
    return $this->belongsTo('App\Models\Store', 'store_id');
  }
  public function logs()
  {
    return $this->hasMany('App\Models\TransactionLog', 'transaction_id');
  }

  public function scopeStoreTransactionQuery($query, $status)
  {
    return $query->whereStore_id(auth()->user()->store->id)->whereStatus($status);
  }
  public function getStatusColor()
  {
    $statusText = $this->status;
    switch ($this->status) {
      case 'unprocessed':
        $statusText = 'text-gray-600';
        break;
      case 'confirmed':
        $statusText = 'text-blue-600';
        break;
      case 'accepted':
        $statusText = 'text-blue-600';
        break;
      case 'processed':
        $statusText = 'text-blue-600';
        break;
      case 'finished':
        $statusText = 'text-green-600';
        break;
      case 'rejected':
        $statusText = 'text-red-600';
        break;
      case 'complain':
        $statusText = 'text-red-600';
        break;
      case 'cancelled':
        $statusText = 'text-red-600';
        break;
      case 'inspection':
        $statusText = 'text-orange-600';
        break;

      default:
        break;
    }

    return $statusText;
  }
  public function getStatusText()
  {
    $statusText = $this->status;
    switch ($this->status) {
      case 'unprocessed':
        $statusText = 'Belum Bayar';
        break;
      case 'confirmed':
        $statusText = 'Menunggu Konfirmasi';
        break;
      case 'accepted':
        $statusText = 'Dikonfirmasi';
        break;
      case 'processed':
        $statusText = 'Diproses';
        break;
      case 'finished':
        $statusText = 'Selesai';
        break;
      case 'rejected':
        $statusText = 'Ditolak';
        break;
      case 'complain':
        $statusText = 'Dikomplain';
        break;
      case 'cancelled':
        $statusText = 'Dibatalkan';
        break;
      case 'inspection':
        $statusText = 'Sedang Diperiksa';
        break;

      default:
        break;
    }

    return $statusText;
  }
}
