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
    Schema::table('payments', function (Blueprint $table) {
      $table->bigInteger('fee_wrap_up')->default(0);
      $table->bigInteger('fee_total')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('payments', function (Blueprint $table) {
      $table->dropColumn(['fee_wrap_up', 'fee_total']);
    });
  }
};
