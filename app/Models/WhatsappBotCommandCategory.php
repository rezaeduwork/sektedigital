<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappBotCommandCategory extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'store_id',
    'is_master',
    'display_order',
    'is_active'
  ];

  protected $casts = [
    'is_master' => 'boolean',
    'is_active' => 'boolean',
  ];

  /**
   * Get the store that owns the category.
   */
  public function store()
  {
    return $this->belongsTo(Store::class);
  }

  /**
   * Get commands that belong to this category.
   */
  public function commands()
  {
    return $this->hasMany(WhatsappBotCommand::class, 'category_id');
  }

  /**
   * Scope a query to only include active categories.
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * Scope a query to order categories by display order.
   */
  public function scopeOrdered($query)
  {
    return $query->orderBy('display_order', 'asc');
  }

  /**
   * Scope a query to include master categories.
   */
  public function scopeMaster($query)
  {
    return $query->where('is_master', true);
  }
}
