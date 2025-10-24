<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // បន្ថែម Columns ថ្មី
        $table->string('light_background_type')->default('default')->after('remember_token');
        $table->string('light_background_value')->nullable()->after('light_background_type');
        $table->string('dark_background_type')->default('default')->after('light_background_value');
        $table->string('dark_background_value')->nullable()->after('dark_background_type');

        // អ្នកអាចលុប Column ចាស់ចោល បើអ្នកចង់
        // $table->dropColumn(['background_type', 'background_value']);
        // ប៉ុន្តែខ្ញុំសូមណែនាំឲ្យទុកវាសិន រួចចាំលុបពេលក្រោយ ពេលប្រាកដថាអ្វីៗដំណើរការ
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
