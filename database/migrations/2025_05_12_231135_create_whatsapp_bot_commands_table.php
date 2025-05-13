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
    Schema::create('whatsapp_bot_commands', function (Blueprint $table) {
      $table->id();
      $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
      $table->string('command'); // The command keyword (e.g., 'products', 'help', 'checkout')
      $table->string('description'); // Description of what the command does
      $table->text('response_template'); // Template for the bot's response
      $table->boolean('is_active')->default(true); // Whether this command is active
      $table->json('parameters')->nullable(); // For commands with parameters
      $table->integer('display_order')->default(0); // For ordering commands in help menu
      $table->timestamps();

      // Ensure commands are unique per store
      $table->unique(['store_id', 'command']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('whatsapp_bot_commands');
  }
};
