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
    Schema::create('whatsapp_bot_command_categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('description')->nullable();
      $table->foreignId('store_id')->nullable()->constrained('stores')->onDelete('cascade');
      $table->boolean('is_master')->default(false);
      $table->integer('display_order')->default(0);
      $table->boolean('is_active')->default(true);
      $table->timestamps();

      // Allow global categories (null store_id) and store-specific categories
      // Store can only add one category
      $table->unique(['store_id'], 'whatsapp_bot_categories_store_unique');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('whatsapp_bot_command_categories');
  }
};
