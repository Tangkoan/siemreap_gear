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
        Schema::table('orders', function (Blueprint $table) {
            // បន្ថែម Column ថ្មីសម្រាប់រក្សាទុក Exchange Rate (USD to KHR)
            // កំណត់ជា decimal ដែលអាចទទួលលេខក្បៀស, default 4100
            $table->decimal('exchange_rate_khr', 10, 2)->default(4100.00)->after('due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('exchange_rate_khr');
        });
    }
};
