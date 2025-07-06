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
        Schema::create('purchase_details', function (Blueprint $table) {
            
            $table->id();
            
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->date('date');

            $table->integer('purchase_price');

            $table->string('quantity')->nullable();
            $table->string('unitcost')->nullable();
            $table->string('total')->nullable();
            $table->timestamps();

            // FK Key
            $table->foreign('purchase_id')
                ->references('id')->on('purchases')
                ->onDelete('restrict')  // កុំប្រើ cascade
                ->onUpdate('cascade');

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('restrict')  // កុំប្រើ cascade
                ->onUpdate('cascade');

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
