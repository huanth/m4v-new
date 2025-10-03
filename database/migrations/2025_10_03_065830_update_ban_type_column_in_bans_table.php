<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bans', function (Blueprint $table) {
            // First, change the column to allow new values
            $table->enum('ban_type', ['comment', 'post', 'login', 'full', 'normal', 'super'])->default('full')->change();
        });
        
        // Then update existing values
        DB::statement("UPDATE bans SET ban_type = 'normal' WHERE ban_type IN ('comment', 'post')");
        DB::statement("UPDATE bans SET ban_type = 'super' WHERE ban_type IN ('login', 'full')");
        
        // Finally, restrict to only new values
        Schema::table('bans', function (Blueprint $table) {
            $table->enum('ban_type', ['normal', 'super'])->default('normal')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bans', function (Blueprint $table) {
            // Revert to old ban_type values
            DB::statement("UPDATE bans SET ban_type = 'comment' WHERE ban_type = 'normal'");
            DB::statement("UPDATE bans SET ban_type = 'full' WHERE ban_type = 'super'");
            
            // Change back to old enum values
            $table->enum('ban_type', ['comment', 'post', 'login', 'full'])->default('full')->change();
        });
    }
};
