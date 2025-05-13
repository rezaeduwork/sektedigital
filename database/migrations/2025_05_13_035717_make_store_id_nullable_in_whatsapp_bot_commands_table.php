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
    // First drop the foreign key constraint
    Schema::table('whatsapp_bot_commands', function (Blueprint $table) {
      // Get foreign key name - Laravel uses convention table_column_foreign
      $table->dropForeign('whatsapp_bot_commands_store_id_foreign');
    });

    Schema::table('whatsapp_bot_commands', function (Blueprint $table) {
      // Make store_id nullable
      $table->unsignedBigInteger('store_id')->nullable()->change();

      // Add foreign key back with nullable option
      $table->foreign('store_id')
        ->references('id')
        ->on('stores')
        ->onDelete('cascade');
    });

    // Handle the unique constraint
    // \DB::statement('ALTER TABLE whatsapp_bot_commands DROP INDEX whatsapp_bot_commands_store_id_command_unique');
    // \DB::statement('ALTER TABLE whatsapp_bot_commands ADD UNIQUE KEY whatsapp_bot_commands_store_command_unique(store_id, command)');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // First drop any rows with null store_id as they would violate the non-null constraint
    \DB::table('whatsapp_bot_commands')->whereNull('store_id')->delete();

    // Drop the foreign key constraint
    Schema::table('whatsapp_bot_commands', function (Blueprint $table) {
      $table->dropForeign('whatsapp_bot_commands_store_id_foreign');
    });

    Schema::table('whatsapp_bot_commands', function (Blueprint $table) {
      // Make store_id required again
      $table->unsignedBigInteger('store_id')->nullable(false)->change();

      // Add foreign key back without nullable option
      $table->foreign('store_id')
        ->references('id')
        ->on('stores')
        ->onDelete('cascade');
    });

    // Handle the unique constraint
    \DB::statement('ALTER TABLE whatsapp_bot_commands DROP INDEX whatsapp_bot_commands_store_command_unique');
    \DB::statement('ALTER TABLE whatsapp_bot_commands ADD UNIQUE KEY whatsapp_bot_commands_store_id_command_unique(store_id, command)');
  }
};
