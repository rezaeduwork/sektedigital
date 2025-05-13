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
    Schema::create('whatsapp_customer_sessions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
      $table->string('phone_number'); // Customer's phone number
      $table->string('session_id')->unique(); // Unique identifier for the session
      $table->string('customer_name')->nullable(); // Name of the customer if provided
      $table->json('cart_data')->nullable(); // Current cart data
      $table->string('current_state')->default('browsing'); // browsing, product_detail, checkout, etc.
      $table->json('context_data')->nullable(); // Additional context for the current state
      $table->timestamp('last_interaction_at'); // When was the last interaction
      $table->boolean('is_active')->default(true); // Is this session still active
      $table->timestamps();

      // Indexes for faster lookups
      $table->index(['store_id', 'phone_number']);
      $table->index(['is_active']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('whatsapp_customer_sessions');
  }
};
