<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'related_id',
        'related_type',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the user who receives the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who sent the notification
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the related model (post or comment)
     */
    public function related()
    {
        return $this->morphTo('related', 'related_type', 'related_id');
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Get the URL for this notification
     */
    public function getUrl()
    {
        switch ($this->type) {
            case 'post_like':
            case 'post_comment':
                // For post-related notifications, link to the post
                if ($this->related_type === 'App\Models\GuildPost') {
                    $post = $this->related;
                    return route('guilds.posts.show', [$post->guild_id, $post->id]);
                }
                break;
                
            case 'comment_like':
            case 'comment_reply':
                // For comment-related notifications, link to the post containing the comment
                if ($this->related_type === 'App\Models\GuildPostComment') {
                    $comment = $this->related;
                    $post = $comment->post;
                    return route('guilds.posts.show', [$post->guild_id, $post->id]) . '#comment-' . $comment->id;
                }
                break;
        }
        
        return '#';
    }
}
