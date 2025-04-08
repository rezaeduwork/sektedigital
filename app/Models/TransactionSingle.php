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
    'product_id',
    'customer_email',
    'customer_phone',
    'user_id',
    'payment_id',
    'quantity',
    'data'
  ];
  public function payment()
  {
    return $this->belongsTo(\App\Models\Payment::class, 'payment_id');
  }
  public function product()
  {
    return $this->belongsTo(\App\Models\ProductInstant::class, 'product_id');
  }
  public function getDataAttribute($value)
  {
    if ($value) {
      return json_decode($value, true);
    }
    return null;
  }
  // HELPER
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
        $statusText = 'Menunggu Proses';
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
      case 'store_finished':
        $statusText = 'Menunggu Diselesaikan';
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
