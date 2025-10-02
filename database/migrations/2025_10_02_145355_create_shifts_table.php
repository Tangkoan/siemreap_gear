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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // អ្នកបើកវេន
            $table->decimal('opening_cash', 10, 2)->default(0); // ប្រាក់ចាប់ផ្តើម
            $table->timestamp('opened_at'); // ពេលបើកវេន
            $table->timestamp('closed_at')->nullable(); // ពេលបិទវេន
            $table->decimal('closing_cash', 10, 2)->nullable(); // ប្រាក់ពេលបិទវេន
            $table->text('notes')->nullable(); // កំណត់ចំណាំ
            $table->enum('status', ['open', 'closed'])->default('open'); // ស្ថានភាពវេន
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
