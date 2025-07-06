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
    Schema::table('whatsapp_bot_commands', function (Blueprint $table) {
      $table->foreignId('category_id')->nullable()->after('master_command_id')
        ->constrained('whatsapp_bot_command_categories')
        ->onDelete('set null');

      // Add handler_class for dynamic command execution
      $table->string('handler_class')->nullable()->after('parameters');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('whatsapp_bot_commands', function (Blueprint $table) {
      $table->dropForeign(['category_id']);
      $table->dropColumn('category_id');
      $table->dropColumn('handler_class');
    });
  }
};
