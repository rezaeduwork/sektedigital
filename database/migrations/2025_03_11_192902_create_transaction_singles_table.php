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
    Schema::create('transaction_singles', function (Blueprint $table) {
      $table->id();
      $table->string('status')->default('unprocessed')->comment('unprocessed | confirmed | processed | finished | rejected | cancelled | inspection');
      $table->unsignedBigInteger('amount');
      $table->string('customer_name');
      $table->string('product_name');
      $table->string('customer_email');
      $table->string('customer_phone');
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('payment_id');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transaction_singles');
  }
};
