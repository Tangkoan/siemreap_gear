<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            // លុប field ចាស់ (opening_cash) បើអ្នកបានប្រើ migration ដំបូង
            $table->dropColumn('opening_cash'); 

            // Field ថ្មីសម្រាប់លុយចាប់ផ្តើម
            $table->decimal('opening_cash_usd', 10, 2)->default(0)->after('user_id');
            $table->decimal('opening_cash_khr', 10, 2)->default(0)->after('opening_cash_usd');
            $table->unsignedSmallInteger('exchange_rate')->after('opening_cash_khr'); // រក្សាទុក Exchange Rate
            
            // Field សម្រាប់លុយបិទវេន (កែសម្រួល)
            $table->decimal('closing_cash_usd', 10, 2)->nullable()->after('closing_cash');
            $table->decimal('closing_cash_khr', 10, 2)->nullable()->after('closing_cash_usd');
            $table->dropColumn('closing_cash'); // លុប field ចាស់ (closing_cash) បើមាន
        });
    }

    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            // បើ rollback, បង្កើត field ចាស់ឡើងវិញ (បើមាន)
            $table->decimal('opening_cash', 10, 2)->default(0); 
            $table->decimal('closing_cash', 10, 2)->nullable();
            
            $table->dropColumn(['opening_cash_usd', 'opening_cash_khr', 'exchange_rate', 'closing_cash_usd', 'closing_cash_khr']);
        });
    }
};
