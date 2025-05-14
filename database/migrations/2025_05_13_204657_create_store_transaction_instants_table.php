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
    Schema::create('store_transaction_instants', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->foreignId('store_id')->constrained('stores');
      $table->foreignId('store_product_instant_id')->constrained('store_product_instants');
      $table->string('provider');
      $table->string('customer_no');
      $table->decimal('price', 12, 2);
      $table->decimal('fee', 12, 2)->default(0);
      $table->decimal('total', 12, 2);
      $table->enum('status', ['pending', 'processing', 'success', 'failed', 'refunded'])->default('pending');
      $table->json('data')->nullable();
      $table->json('response_data')->nullable();
      $table->timestamp('processed_at')->nullable();
      $table->timestamp('completed_at')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('store_transaction_instants');
  }
};
