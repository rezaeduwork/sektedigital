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
      $table->boolean('is_master')->default(false)->after('display_order');
      $table->unsignedBigInteger('master_command_id')->nullable()->after('is_master');
      $table->string('name')->nullable()->after('command');

      // Add foreign key constraint
      $table->foreign('master_command_id')
        ->references('id')
        ->on('whatsapp_bot_commands')
        ->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('whatsapp_bot_commands', function (Blueprint $table) {
      $table->dropForeign(['master_command_id']);
      $table->dropColumn(['is_master', 'master_command_id', 'name']);
    });
  }
};
