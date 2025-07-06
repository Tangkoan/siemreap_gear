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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            

            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('warehouse_id');

            $table->string('purchase_date');
            $table->string('purchase_status');

            $table->decimal('discount')->default(0);
            $table->decimal('shipping', 10 , 2)->default(0.00);
            
            $table->string('total_products');
            $table->string('sub_total')->nullable();
            $table->string('vat')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('total')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('pay')->nullable();
            $table->string('due')->nullable();
            $table->timestamps();

            // 
            $table->foreign('supplier_id')
                ->references('id')->on('suppliers')
                ->onDelete('restrict')  // កុំប្រើ cascade
                ->onUpdate('cascade');


                $table->foreign('warehouse_id')
                ->references('id')->on('ware_houses')
                ->onDelete('restrict')  // កុំប្រើ cascade
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
