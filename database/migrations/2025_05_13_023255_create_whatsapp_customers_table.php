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
    Schema::create('whatsapp_customers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
      $table->string('name');
      $table->string('phone_number')->index();
      $table->string('email')->nullable();
      $table->text('address')->nullable();
      $table->json('meta_data')->nullable();
      $table->timestamp('last_interaction_at')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();

      // Add unique constraint for store_id and phone_number combination
      $table->unique(['store_id', 'phone_number']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('whatsapp_customers');
  }
};
