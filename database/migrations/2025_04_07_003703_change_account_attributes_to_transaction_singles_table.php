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
    Schema::table('transaction_singles', function (Blueprint $table) {
      $table->string('customer_name')->nullable()->change();
      $table->string('product_name')->nullable()->change();
      $table->string('customer_email')->nullable()->change();
      $table->string('customer_phone')->nullable()->change();
      $table->unsignedBigInteger('user_id')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('transaction_singles', function (Blueprint $table) {
      $table->string('customer_name')->change();
      $table->string('product_name')->change();
      $table->string('customer_email')->change();
      $table->string('customer_phone')->change();
      $table->unsignedBigInteger('user_id')->change();
    });
  }
};
