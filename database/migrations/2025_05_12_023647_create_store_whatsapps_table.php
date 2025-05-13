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
    Schema::create('store_whatsapps', function (Blueprint $table) {
      $table->id();
      $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
      $table->string('connection_id')->unique();
      $table->string('phone_number')->nullable();
      $table->enum('status', ['disconnected', 'connecting', 'connected'])->default('disconnected');
      $table->text('qr_code')->nullable();
      $table->json('session_data')->nullable();
      $table->timestamp('last_connected_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('store_whatsapps');
  }
};
