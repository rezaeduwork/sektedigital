<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
  use HasFactory;
  protected $fillable = [
    'user_id',
    'user_store_id',
    'store_id'
  ];
  public function chats()
  {
    return $this->hasMany(\App\Models\Chat::class, 'chat_session_id');
  }
}
