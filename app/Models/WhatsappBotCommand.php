<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappBotCommand extends Model
{
  use HasFactory;

  protected $fillable = [
    'store_id',
    'command',
    'description',
    'response_template',
    'is_active',
    'parameters',
    'display_order'
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'parameters' => 'array',
  ];

  /**
   * Get the store that owns the command.
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Scope a query to only include active commands.
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * Scope a query to order commands by display order.
   */
  public function scopeOrdered($query)
  {
    return $query->orderBy('display_order', 'asc');
  }

  /**
   * Scope a query to include global commands (where store_id is null)
   * or store-specific commands.
   */
  public function scopeForStore($query, $storeId)
  {
    return $query->where(function ($q) use ($storeId) {
      $q->where('store_id', $storeId)
        ->orWhereNull('store_id');
    });
  }
}
