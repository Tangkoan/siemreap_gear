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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            
            // ភ្ជាប់ទៅតារាង categories
            $table->foreignId('expense_category_id')
                  ->constrained('expense_categories')
                  ->onDelete('cascade'); // បើលុប category, expense ក៏ត្រូវលុបដែរ

            // ភ្ជាប់ទៅអ្នកប្រើប្រាស់ (អ្នកដែលបានកត់ត្រា) (optional)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->string('description'); // ការពិពណ៌នា (ឧ: ថ្លៃភ្លើងខែ១០)
            $table->decimal('amount', 10, 2); // ចំនួនទឹកប្រាក់
            $table->date('expense_date'); // ថ្ងៃខែដែលបានចំណាយ
            $table->text('notes')->nullable(); // កំណត់ចំណាំបន្ថែម
            $table->string('receipt_image')->nullable(); // រូបភាពវិក្កយបត្រ (optional)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
