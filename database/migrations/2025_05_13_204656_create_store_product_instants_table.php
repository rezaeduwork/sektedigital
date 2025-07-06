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
    Schema::create('store_product_instants', function (Blueprint $table) {
      $table->id();
      $table->foreignId('store_id')->constrained('stores');
      $table->foreignId('product_instant_id')->constrained('product_instants');
      $table->string('code');
      $table->string('provider');
      $table->string('brand')->nullable();
      $table->string('category')->nullable();
      $table->string('title');
      $table->text('highlight')->nullable();
      $table->text('description')->nullable();
      $table->decimal('price', 12, 2)->default(0);
      $table->decimal('selling_price', 12, 2)->default(0);
      $table->string('slug')->nullable();
      $table->integer('stock')->default(0);
      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->string('image')->nullable();
      $table->string('type')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('store_product_instants');
  }
};
