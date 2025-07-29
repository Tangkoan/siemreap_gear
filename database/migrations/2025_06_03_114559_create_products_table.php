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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('condition_id');
            $table->string('product_code')->unique();
            $table->string('product_image');
            $table->string('product_store')->nullable();
            $table->integer('stock_alert')->default(0);

            $table->string('product_detail')->nullable();

            $table->string('buying_date')->nullable();
            $table->string('buying_price')->nullable();
            $table->string('selling_price')->nullable();
            $table->string('cost')->nullable();
            $table->string('expire_date')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->timestamps();
        
            // Foreign keys

            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('restrict')  // កុំប្រើ cascade
                ->onUpdate('cascade');
            $table->foreign('supplier_id')
                ->references('id')->on('suppliers')
                ->onDelete('restrict')  // កុំប្រើ cascade
                ->onUpdate('cascade');

            $table->foreign('condition_id')
                ->references('id')->on('conditions')
                ->onDelete('restrict')  // កុំប្រើ cascade
                ->onUpdate('cascade');

            
            
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
