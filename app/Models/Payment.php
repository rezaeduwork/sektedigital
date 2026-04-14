<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  use HasFactory;
  protected $fillable = [
    // comment('pending | settlement | expired | failed')
    'status',
    'token',
    'amount',
    'user_id',
    'settlement_at',
    'data',
    'fee_amount',
    'fee_wrap_up',
    'fee_total',
    'proof_text',
    'proof_file',
    // comment('basic | instant | deposit')
    'transaction_type',
    'expired_at',
    'payment_gateway'
  ];

  protected $casts = [
    'data' => 'array',
  ];

  public function getDataAttribute($value)
  {
    try {
      if (is_string($value) && $value) {
        $value = stripslashes($value); // hapus backslash
        return json_decode($value, true);
      } else if (is_array($value)) {
        return $value;
      }
    } catch (\Throwable $th) {
      //throw $th;
    }
    return null;
  }

  public function user()
  {
    return $this->belongsTo('App\Models\User', 'user_id');
  }
  public function transactions()
  {
    return $this->hasMany('App\Models\Transaction', 'payment_id');
  }
  public function singleTransaction()
  {
    return $this->hasOne('App\Models\TransactionSingle', 'payment_id');
  }
  public function getStatusColor()
  {
    $statusText = $this->status;
    switch ($this->status) {
      case 'settlement':
        $statusText = 'text-green-600';
        break;
      case 'pending':
        $statusText = 'text-orange-600';
        break;
      case 'expired':
        $statusText = 'text-red-600';
        break;
      case 'failed':
        $statusText = 'text-red-600';
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
      case 'settlement':
        $statusText = 'Sudah Dibayar';
        break;
      case 'pending':
        $statusText = 'Belum Dibayar';
        break;
      case 'expired':
        $statusText = 'Kedaluarsa';
        break;
      case 'failed':
        $statusText = 'Gagal';
        break;

      default:
        break;
    }

    return $statusText;
  }

  /**
   * Get payment gateway relationship
   */
  public function gateway()
  {
    return $this->belongsTo('App\Models\PaymentGateway', 'payment_gateway', 'name');
  }
}
