<?php

namespace App\Services;

use App\Models\Notification as AppNotification;
use App\Models\User;
use App\Models\GuildPost;
use App\Models\GuildPostComment;

class NotificationService
{
    /**
     * Create a notification for post like
     */
    public static function createPostLikeNotification($postId, $fromUserId)
    {
        $post = GuildPost::find($postId);
        if (!$post || $post->author_id == $fromUserId) {
            return; // Don't notify if user likes their own post
        }

        $fromUser = User::find($fromUserId);
        $message = $fromUser->username . ' đã thích bài viết của bạn';
        
        AppNotification::create([
            'user_id' => $post->author_id,
            'from_user_id' => $fromUserId,
            'type' => 'post_like',
            'related_id' => $postId,
            'related_type' => 'GuildPost',
            'message' => $message,
        ]);
    }

    /**
     * Create a notification for post comment
     */
    public static function createPostCommentNotification($postId, $fromUserId)
    {
        $post = GuildPost::find($postId);
        if (!$post || $post->author_id == $fromUserId) {
            return; // Don't notify if user comments on their own post
        }

        $fromUser = User::find($fromUserId);
        $message = $fromUser->username . ' đã bình luận bài viết của bạn';
        
        AppNotification::create([
            'user_id' => $post->author_id,
            'from_user_id' => $fromUserId,
            'type' => 'post_comment',
            'related_id' => $postId,
            'related_type' => 'GuildPost',
            'message' => $message,
        ]);
    }

    /**
     * Create a notification for comment like
     */
    public static function createCommentLikeNotification($commentId, $fromUserId)
    {
        $comment = GuildPostComment::find($commentId);
        if (!$comment || $comment->user_id == $fromUserId) {
            return; // Don't notify if user likes their own comment
        }

        $fromUser = User::find($fromUserId);
        $message = $fromUser->username . ' đã thích bình luận của bạn';
        
        AppNotification::create([
            'user_id' => $comment->user_id,
            'from_user_id' => $fromUserId,
            'type' => 'comment_like',
            'related_id' => $commentId,
            'related_type' => 'GuildPostComment',
            'message' => $message,
        ]);
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead($notificationId, $userId)
    {
        $notification = AppNotification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();
            
        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead($userId)
    {
        AppNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Get unread notifications count for a user
     */
    public static function getUnreadCount($userId)
    {
        return AppNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get notifications for a user
     */
    public static function getNotifications($userId, $limit = 20)
    {
        return AppNotification::where('user_id', $userId)
            ->with(['fromUser', 'related'])
            ->latest()
            ->limit($limit)
            ->get();
    }
}
