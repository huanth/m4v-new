<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildPostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'quoted_content',
        'content',
    ];

    /**
     * Get the post this comment belongs to
     */
    public function post()
    {
        return $this->belongsTo(GuildPost::class, 'post_id');
    }

    /**
     * Get the user who made this comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all likes for this comment
     */
    public function likes()
    {
        return $this->hasMany(GuildCommentLike::class, 'comment_id');
    }

    /**
     * Get likes count for this comment
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Check if user has liked this comment
     */
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Check if user can edit this comment
     */
    public function canEdit($userId)
    {
        // Author can always edit
        if ($this->user_id == $userId) {
            return true;
        }

        // Guild leaders and vice leaders can edit
        $guild = $this->post->guild;
        $userMembership = $guild->members()->where('user_id', $userId)->first();
        
        if ($userMembership && $userMembership->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete this comment
     */
    public function canDelete($userId)
    {
        // Author can delete
        if ($this->user_id == $userId) {
            return true;
        }

        // Guild leaders and vice leaders can delete
        $guild = $this->post->guild;
        $userMembership = $guild->members()->where('user_id', $userId)->first();
        
        if ($userMembership && $userMembership->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Get the parent comment (if this is a reply)
     */
    public function parent()
    {
        return $this->belongsTo(GuildPostComment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment
     */
    public function replies()
    {
        return $this->hasMany(GuildPostComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Check if this comment is a reply
     */
    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    /**
     * Get the nesting level of this comment
     */
    public function getLevel()
    {
        $level = 0;
        $current = $this;
        while ($current->parent_id) {
            $level++;
            $current = $current->parent;
            if ($level > 10) break; // Prevent infinite loops
        }
        return $level;
    }
}