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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            $table->decimal('starting_cash', 10, 2);
            $table->decimal('ending_cash', 10, 2)->nullable();

            // ផ្នែកនេះ ពេលបិទវេន ប្រព័ន្ធនឹងគណនាបំពេញ
            $table->decimal('total_sales_cash', 10, 2)->default(0);
            $table->decimal('total_sales_card', 10, 2)->default(0);
            $table->decimal('total_sales_qr', 10, 2)->default(0);
            $table->decimal('total_sales_other', 10, 2)->default(0);

            $table->decimal('difference', 10, 2)->default(0);
            $table->string('status')->default('open')->comment('open, closed'); // ស្ថានភាពវេន
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
