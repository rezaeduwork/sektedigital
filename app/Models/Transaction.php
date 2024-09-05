<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  use HasFactory;
  // comment('unprocessed | confirmed | accepted | processed | finished | rejected | cancelled | inspection')
  protected $fillable = [
    'status',
    'amount',
    'customer_name',
    'customer_email',
    'customer_phone',
    'user_id'
  ];
  public function details()
  {
    return $this->hasMany('App\Models\TransactionDetail', 'transaction_id');
  }
  public function logs()
  {
    return $this->hasMany('App\Models\TransactionLog', 'transaction_id');
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
