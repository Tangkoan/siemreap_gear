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
        Schema::table('products', function (Blueprint $table) {
            // បន្ថែម Unique Index ទៅលើ product_code
            $table->string('product_code', 125)->unique()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // លុប Unique Index ចេញវិញ ពេល Rollback
            $table->string('product_code', 125)->dropUnique()->change(); 
        });
    }
};
