<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'guild_id',
        'category_id',
        'author_id',
        'title',
        'content',
        'is_pinned',
        'is_locked',
        'views_count',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'views_count' => 'integer',
    ];

    /**
     * Get the guild this post belongs to
     */
    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }

    /**
     * Get the category this post belongs to
     */
    public function category()
    {
        return $this->belongsTo(GuildCategory::class);
    }

    /**
     * Get the author of this post
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Alias for author relationship
     */
    public function user()
    {
        return $this->author();
    }

    /**
     * Scope to order posts by pinned first, then by recency and activity, then by created_at
     */
    public function scopeOrdered($query)
    {
        return $query->selectRaw('guild_posts.*, 
                (SELECT COUNT(*) FROM guild_post_likes WHERE guild_post_likes.post_id = guild_posts.id) as likes_count,
                (SELECT COUNT(*) FROM guild_post_comments WHERE guild_post_comments.post_id = guild_posts.id) as comments_count,
                TIMESTAMPDIFF(HOUR, guild_posts.created_at, NOW()) as hours_ago')
                    ->orderBy('is_pinned', 'desc')
                    ->orderByRaw('
                        CASE 
                            WHEN (likes_count + comments_count) > 0 AND TIMESTAMPDIFF(HOUR, created_at, NOW()) <= 24 THEN 1
                            WHEN TIMESTAMPDIFF(HOUR, created_at, NOW()) <= 2 THEN 2
                            WHEN (likes_count + comments_count) > 0 THEN 3
                            ELSE 4
                        END,
                        (likes_count + comments_count) DESC,
                        created_at DESC
                    ');
    }

    /**
     * Scope to filter by category
     */
    public function scopeInCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    /**
     * Check if user can edit this post
     */
    public function canEdit($userId)
    {
        // Author can always edit
        if ($this->author_id == $userId) {
            return true;
        }

        // Guild leaders and vice leaders can edit
        $guild = $this->guild;
        $userMembership = $guild->members()->where('user_id', $userId)->first();
        
        if ($userMembership && $userMembership->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete this post
     */
    public function canDelete($userId)
    {
        // Author can delete
        if ($this->author_id == $userId) {
            return true;
        }

        // Guild leaders and vice leaders can delete
        $guild = $this->guild;
        $userMembership = $guild->members()->where('user_id', $userId)->first();
        
        if ($userMembership && $userMembership->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can pin/unpin this post
     */
    public function canPin($userId)
    {
        // Only guild leaders and vice leaders can pin
        $guild = $this->guild;
        $userMembership = $guild->members()->where('user_id', $userId)->first();
        
        return $userMembership && $userMembership->isAdmin();
    }

    /**
     * Check if user can lock/unlock this post
     */
    public function canLock($userId)
    {
        // Only guild leaders and vice leaders can lock
        $guild = $this->guild;
        $userMembership = $guild->members()->where('user_id', $userId)->first();
        
        return $userMembership && $userMembership->isAdmin();
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Get all likes for this post
     */
    public function likes()
    {
        return $this->hasMany(GuildPostLike::class, 'post_id');
    }

    /**
     * Get all comments for this post (flat structure)
     */
    public function comments()
    {
        return $this->hasMany(GuildPostComment::class, 'post_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get likes count for this post
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Get comments count for this post (including replies)
     */
    public function getCommentsCountAttribute()
    {
        return $this->hasMany(GuildPostComment::class, 'post_id')->count();
    }

    /**
     * Get total comments count for this post (for display purposes)
     */
    public function getTotalCommentsCount()
    {
        return $this->hasMany(GuildPostComment::class, 'post_id')->count();
    }

    /**
     * Check if user has liked this post
     */
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Get excerpt of content
     */
    public function getExcerptAttribute($length = 150)
    {
        return strlen($this->content) > $length 
            ? substr($this->content, 0, $length) . '...' 
            : $this->content;
    }
}