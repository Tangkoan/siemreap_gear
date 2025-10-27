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
        Schema::table('users', function (Blueprint $table) {
            // បន្ថែម Column ថ្មីសម្រាប់ផ្ទុកការកំណត់ទាំងអស់
            $table->json('appearance_settings')->nullable()->after('background_value');

            // (ស្រេចចិត្ត) អ្នកអាចលុប Column ចាស់ចោល បន្ទាប់ពីផ្ទេរទិន្នន័យរួច
            // $table->dropColumn(['background_type', 'background_value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('appearance_settings');
            // បើអ្នកបានលុប Column ចាស់ សូមបន្ថែមវាមកវិញនៅទីនេះ
        });
    }
};