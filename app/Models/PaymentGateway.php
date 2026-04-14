<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'display_name',
    'status',
    'data',
    'description',
  ];

  protected $casts = [
    'data' => 'array',
  ];

  /**
   * Check if gateway is active
   */
  public function isActive(): bool
  {
    return $this->status === 'active';
  }

  /**
   * Scope to get active gateways
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Get a specific data value
   */
  public function getData(string $key, $default = null)
  {
    return data_get($this->data, $key, $default);
  }

  /**
   * Set a specific data value
   */
  public function setData(string $key, $value): void
  {
    $data = $this->data ?? [];
    data_set($data, $key, $value);
    $this->data = $data;
  }
}
