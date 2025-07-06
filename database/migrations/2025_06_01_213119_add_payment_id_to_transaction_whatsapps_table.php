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
        Schema::table('transaction_whatsapps', function (Blueprint $table) {
            // Add payment_id column
            $table->bigInteger('payment_id')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_whatsapps', function (Blueprint $table) {
            $table->dropColumn('payment_id');
        });
    }
};
