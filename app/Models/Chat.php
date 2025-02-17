<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
  use HasFactory;
  protected $fillable = [
    'text',
    'sender_id',
    'receiver_id',
    'reply_id',
    'chat_session_id',
    // comment('text | file')
    'type'
  ];
  public function chatSession()
  {
    return $this->belongsTo(\App\Models\ChatSession::class, 'chat_session_id');
  }
}
