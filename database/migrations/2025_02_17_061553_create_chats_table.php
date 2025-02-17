<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('chats', function (Blueprint $table) {
      $table->id();
      $table->text('text');
      $table->string('type')->comment('text | file');
      $table->unsignedBigInteger('sender_id');
      $table->unsignedBigInteger('receiver_id');
      $table->unsignedBigInteger('reply_id')->nullable();
      $table->unsignedBigInteger('chat_session_id');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('chats');
  }
};
