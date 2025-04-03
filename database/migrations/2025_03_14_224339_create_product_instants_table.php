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
    Schema::create('product_instants', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->string('slug');
      $table->string('provider')->nullable();
      $table->string('category');
      $table->string('brand')->nullable();
      $table->string('title');
      $table->string('highlight')->nullable();
      $table->string('description')->nullable();
      $table->integer('price');
      $table->double('stock');
      $table->string('status')->comment('active | inactive');
      $table->string('provider_buyer_status')->default('unset')->comment('unset | active | inactive');
      $table->double('provider_stock')->default(-1);
      $table->string('image')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_instants');
  }
};
