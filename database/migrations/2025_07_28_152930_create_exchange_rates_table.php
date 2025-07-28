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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->date('rate_date')->unique(); // နေ့ខែសម្រាប់អត្រាប្តូរប្រាក់
            $table->decimal('rate_khr', 10, 2); // តម្លៃអត្រាប្តូរប្រាក់ (KHR per USD)
            $table->boolean('is_active')->default(true); // ដើម្បីកំណត់ថា Rate មួយណាកំពុងប្រើ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
