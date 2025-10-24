<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_background_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 'default', 'color', 'image'
            $table->string('background_type')->default('default')->after('photo'); 
            $table->string('background_value')->nullable()->after('background_type'); // Stores color code or image path
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['background_type', 'background_value']);
        });
    }
};