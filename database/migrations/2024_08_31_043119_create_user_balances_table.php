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
    Schema::create('user_balances', function (Blueprint $table) {
      $table->id();
      $table->string('uid');
      $table->string('token')->nullable();
      $table->string('type');
      $table->string('status');
      $table->bigInteger('amount');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_balances');
  }
};
