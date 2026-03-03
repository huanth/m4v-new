<?php

namespace App\Services;

use App\Models\GuildPost;
use App\Models\GuildPostLike;
use App\Services\NotificationService;

class GuildPostService
{
    /**
     * Create a new post
     */
    public function createPost(int $guildId, int $userId, array $data): GuildPost
    {
        return GuildPost::create([
            'guild_id' => $guildId,
            'category_id' => $data['category_id'] ?? null,
            'author_id' => $userId,
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }

    /**
     * Update an existing post
     */
    public function updatePost(GuildPost $post, array $data): bool
    {
        return $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'category_id' => $data['category_id'] ?? null,
        ]);
    }

    /**
     * Delete a post
     */
    public function deletePost(GuildPost $post): bool
    {
        return $post->delete();
    }

    /**
     * Toggle Pin status
     */
    public function togglePin(GuildPost $post): bool
    {
        return $post->update(['is_pinned' => !$post->is_pinned]);
    }

    /**
     * Toggle Lock status
     */
    public function toggleLock(GuildPost $post): bool
    {
        return $post->update(['is_locked' => !$post->is_locked]);
    }

    /**
     * Toggle Like on a post
     * Returns true if liked, false if unliked
     */
    public function toggleLike(GuildPost $post, int $userId): bool
    {
        $existingLike = GuildPostLike::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return false;
        }

        GuildPostLike::create([
            'post_id' => $post->id,
            'user_id' => $userId,
        ]);

        NotificationService::createPostLikeNotification($post->id, $userId);
        
        return true;
    }
}
