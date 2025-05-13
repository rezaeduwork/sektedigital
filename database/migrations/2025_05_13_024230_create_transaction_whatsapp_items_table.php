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
    Schema::create('transaction_whatsapp_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('transaction_whatsapp_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
      $table->integer('quantity')->default(1);
      $table->decimal('price', 12, 2);
      $table->decimal('total', 12, 2);
      $table->json('options')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transaction_whatsapp_items');
  }
};
