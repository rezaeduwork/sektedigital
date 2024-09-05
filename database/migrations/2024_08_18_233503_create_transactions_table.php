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
    Schema::create('transactions', function (Blueprint $table) {
      $table->id();
      $table->string('status')->default('unprocessed')->comment('unprocessed | confirmed | accepted | processed | finished | rejected | cancelled | inspection');
      $table->unsignedBigInteger('amount');
      $table->string('customer_name');
      $table->string('customer_email');
      $table->string('customer_phone');
      $table->unsignedBigInteger('user_id');
      $table->timestamps();

      $table->index('user_id');
      $table->index('status');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transactions');
  }
};
