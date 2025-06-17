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
        Schema::create('orderdetails', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');


            $table->string('quantity')->nullable();
            $table->string('unitcost')->nullable();
            $table->string('total')->nullable();
            $table->timestamps();
            
            // FK Key
            $table->foreign('order_id')
                ->references('id')->on('orders')
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
        Schema::dropIfExists('orderdetails');
    }
};
