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

    // Support both shortname (legacy) and FQN for morphTo resolution
    protected $morphMap = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function related()
    {
        return $this->morphTo('related', 'related_type', 'related_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Resolve the related model manually, supporting both shortname and FQN.
     */
    private function resolveRelated(): ?Model
    {
        $typeMap = [
            'GuildPost'         => GuildPost::class,
            'GuildPostComment'  => GuildPostComment::class,
            GuildPost::class    => GuildPost::class,
            GuildPostComment::class => GuildPostComment::class,
        ];

        $modelClass = $typeMap[$this->related_type] ?? null;
        if (!$modelClass || !$this->related_id) {
            return null;
        }

        return $modelClass::find($this->related_id);
    }

    /**
     * Get the URL for this notification.
     * Returns '/' as fallback so redirect is always safe.
     */
    public function getUrl(): string
    {
        $related = $this->resolveRelated();

        switch ($this->type) {
            case 'post_like':
            case 'post_comment':
                /** @var GuildPost|null $post */
                $post = $related;
                if ($post) {
                    return route('guilds.posts.show', [$post->guild_id, $post->id]);
                }
                break;

            case 'comment_like':
            case 'comment_reply':
                /** @var GuildPostComment|null $comment */
                $comment = $related;
                if ($comment) {
                    $post = $comment->post ?? GuildPost::find($comment->post_id);
                    if ($post) {
                        return route('guilds.posts.show', [$post->guild_id, $post->id])
                            . '#comment-' . $comment->id;
                    }
                }
                break;
        }

        return '/';
    }
}
