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
      // Change data column from text to json for better handling
      $table->json('data')->nullable()->change();

      // Add payment_gateway column to track which gateway was used
      $table->string('payment_gateway')->nullable()->after('status')
        ->comment('tripay | xendit | sakurupiah | paymenku');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('payments', function (Blueprint $table) {
      $table->text('data')->nullable()->change();
      $table->dropColumn('payment_gateway');
    });
  }
};
