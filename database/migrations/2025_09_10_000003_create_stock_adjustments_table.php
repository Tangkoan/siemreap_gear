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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // អ្នកដែលធ្វើការកែប្រែ
            $table->string('type');
            $table->integer('quantity'); 
            $table->integer('before_quantity'); // ចំនួនស្ដុកមុនពេលកែ
            $table->integer('after_quantity'); // ចំនួនស្ដុកក្រោយពេលកែ
            $table->text('notes')->nullable(); // កំណត់ចំណាំ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
