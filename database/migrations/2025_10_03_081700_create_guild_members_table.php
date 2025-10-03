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
        Schema::create('guild_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guild_id')->constrained('guilds')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['leader', 'vice_leader', 'elder', 'member'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            // Indexes
            $table->index(['guild_id', 'user_id']);
            $table->index('role');
            $table->unique(['guild_id', 'user_id']); // Một user chỉ có thể ở một guild
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guild_members');
    }
};
