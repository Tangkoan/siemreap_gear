<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ឈ្មោះបុគ្គលិក
            $table->string('phone')->nullable(); // លេខទូរស័ព្ទ
            $table->string('position'); // តួនាទី (ឧ: IT Backend, Accountant)
            
            // នេះជាចំណុចសំខាន់ (ប្រាក់ខែគោល គឺសម្រាប់បុគ្គលម្នាក់ៗ)
            $table->decimal('base_salary', 10, 2); 

            $table->date('join_date')->nullable(); // ថ្ងៃចូលធ្វើការ
            $table->enum('status', ['active', 'inactive'])->default('active'); // ស្ថានភាព
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};