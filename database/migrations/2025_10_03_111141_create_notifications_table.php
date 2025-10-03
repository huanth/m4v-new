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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Người nhận thông báo
            $table->unsignedBigInteger('from_user_id'); // Người gửi thông báo
            $table->string('type'); // post_like, post_comment, comment_like
            $table->unsignedBigInteger('related_id'); // ID của bài viết hoặc comment
            $table->string('related_type'); // GuildPost, GuildPostComment
            $table->text('message'); // Nội dung thông báo
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
