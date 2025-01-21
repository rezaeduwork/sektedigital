<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  use HasFactory;
  protected $fillable = [
    // comment('pending | settlement')
    'status',
    'token',
    'amount',
    'user_id',
    'settlement_at',
    'data'
  ];
  public function transactions()
  {
    return $this->hasMany('App\Models\Transaction', 'payment_id');
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

      default:
        break;
    }

    return $statusText;
  }
}
