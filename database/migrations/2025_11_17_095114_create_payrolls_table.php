<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            // ភ្ជាប់ទៅតារាង employees
            $table->foreignId('employee_id')
                  ->constrained('employees')
                  ->onDelete('cascade'); // បើលុបបុគ្គលិក, payroll ក៏ត្រូវលុបដែរ

            $table->date('payment_date'); // ថ្ងៃបើកប្រាក់ខែ
            $table->string('month_year'); // ឧ: "Nov-2025" (សម្រាប់ខែ/ឆ្នាំ ណា)
            
            $table->decimal('base_salary', 10, 2); // ប្រាក់ខែគោល (ទាញមកពី employee)
            $table->decimal('bonus', 10, 2)->default(0); // ប្រាក់បូក
            $table->decimal('deduction', 10, 2)->default(0); // ប្រាក់កាត់
            $table->decimal('net_salary', 10, 2); // ប្រាក់ខែសុទ្ធ (Net)

            $table->text('notes')->nullable(); // កំណត់ចំណាំ
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};