<?php

namespace App\Services;

use App\Models\GuildPostComment;
use App\Models\GuildCommentLike;
use App\Services\NotificationService;

class GuildCommentService
{
    /**
     * Create a new comment or reply
     */
    public function createComment(int $postId, int $userId, array $data): GuildPostComment
    {
        $quotedContent = null;
        if (!empty($data['parent_id']) && !empty($data['include_quote']) && !empty($data['quoted_content'])) {
            $quotedContent = $data['quoted_content'];
        }

        $comment = GuildPostComment::create([
            'post_id' => $postId,
            'user_id' => $userId,
            'parent_id' => $data['parent_id'] ?? null,
            'quoted_content' => $quotedContent,
            'content' => $data['content'],
        ]);

        \Cache::forget("post_activity_{$postId}");

        NotificationService::createPostCommentNotification($postId, $userId);

        if (!empty($data['parent_id'])) {
            $parentComment = GuildPostComment::find($data['parent_id']);
            if ($parentComment && $parentComment->user_id != $userId) {
                NotificationService::createCommentReplyNotification($comment->id, $parentComment->user_id);
            }
        }

        return $comment;
    }

    /**
     * Update an existing comment
     */
    public function updateComment(GuildPostComment $comment, array $data): bool
    {
        return $comment->update(['content' => $data['content']]);
    }

    /**
     * Delete a comment
     */
    public function deleteComment(GuildPostComment $comment): bool
    {
        return $comment->delete();
    }

    /**
     * Toggle Like on a comment
     * Returns true if liked, false if unliked
     */
    public function toggleLike(GuildPostComment $comment, int $userId): bool
    {
        $existingLike = GuildCommentLike::where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return false;
        }

        GuildCommentLike::create([
            'comment_id' => $comment->id,
            'user_id' => $userId,
        ]);

        NotificationService::createCommentLikeNotification($comment->id, $userId);

        return true;
    }
}
