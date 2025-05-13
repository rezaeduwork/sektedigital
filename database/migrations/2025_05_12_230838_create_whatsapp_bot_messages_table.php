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
    Schema::create('whatsapp_bot_messages', function (Blueprint $table) {
      $table->id();
      $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
      $table->string('phone_number'); // Customer's phone number
      $table->enum('direction', ['incoming', 'outgoing']); // Message direction
      $table->text('message'); // Message content
      $table->string('message_type')->default('text'); // text, image, document, etc.
      $table->json('media_data')->nullable(); // For storing media file info
      $table->boolean('is_processed')->default(false);
      $table->string('session_id')->nullable(); // For tracking conversation sessions
      $table->string('context')->nullable(); // Current context (product listing, checkout, etc)
      $table->timestamps();

      // Index for faster lookups
      $table->index(['store_id', 'phone_number']);
      $table->index(['session_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('whatsapp_bot_messages');
  }
};
