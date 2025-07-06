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
    'display_order',
    'is_master',
    'master_command_id',
    'category_id',
    'handler_class',
    'state',
    // unused column
    'name'
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'parameters' => 'array',
    'is_master' => 'boolean',
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

  /**
   * Get the master command that this custom command is based on.
   */
  public function masterCommand()
  {
    return $this->belongsTo(WhatsappBotCommand::class, 'master_command_id');
  }

  /**
   * Get all custom commands based on this master command.
   */
  public function customCommands()
  {
    return $this->hasMany(WhatsappBotCommand::class, 'master_command_id');
  }

  /**
   * Scope a query to only include master commands.
   */
  public function scopeMaster($query)
  {
    return $query->where('is_master', true);
  }

  /**
   * Scope a query to only include custom commands.
   */
  public function scopeCustom($query)
  {
    return $query->where('is_master', false);
  }

  /**
   * Get the category that this command belongs to.
   */
  public function category()
  {
    return $this->belongsTo(WhatsappBotCommandCategory::class, 'category_id');
  }

  /**
   * Execute the command handler if available.
   *
   * @param array $params Parameters to pass to the handler
   * @return mixed|null The result of the handler or null if no handler exists
   */
  public function executeHandler($params = [])
  {
    if (!$this->handler_class || !class_exists($this->handler_class)) {
      return null;
    }

    try {
      $handlerInstance = app($this->handler_class);
      if (method_exists($handlerInstance, 'handle')) {
        return $handlerInstance->handle($this, $params);
      }
    } catch (\Exception $e) {
      \Log::error('Error executing command handler: ' . $e->getMessage(), [
        'command' => $this->command,
        'handler_class' => $this->handler_class,
        'exception' => $e
      ]);
    }

    return null;
  }

  /**
   * Scope a query to filter commands by category.
   */
  public function scopeInCategory($query, $categoryId)
  {
    return $query->where('category_id', $categoryId);
  }

  /**
   * Scope a query to get commands without a category.
   */
  public function scopeWithoutCategory($query)
  {
    return $query->whereNull('category_id');
  }
}
