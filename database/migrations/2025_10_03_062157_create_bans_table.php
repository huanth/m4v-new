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
        Schema::create('bans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User bị ban
            $table->foreignId('banned_by')->constrained('users')->onDelete('cascade'); // User thực hiện ban
            $table->string('reason'); // Lý do ban
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->timestamp('banned_at'); // Thời gian ban
            $table->timestamp('expires_at')->nullable(); // Thời gian hết hạn (null = permanent)
            $table->boolean('is_permanent')->default(false); // Ban vĩnh viễn
            $table->boolean('is_active')->default(true); // Ban còn hiệu lực
            $table->enum('ban_type', ['comment', 'post', 'login', 'full'])->default('full'); // Loại ban
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bans');
    }
};
