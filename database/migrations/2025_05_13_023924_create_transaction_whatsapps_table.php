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
    Schema::create('transaction_whatsapps', function (Blueprint $table) {
      $table->id();
      $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
      $table->foreignId('whatsapp_customer_id')->constrained('whatsapp_customers')->onDelete('cascade');
      $table->string('order_number')->unique();
      $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
      $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
      $table->string('payment_method')->default('WhatsApp');
      $table->decimal('total', 12, 2)->default(0);
      $table->text('notes')->nullable();
      $table->text('shipping_address')->nullable();
      $table->string('source')->default('whatsapp_bot');
      $table->json('meta_data')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transaction_whatsapps');
  }
};
