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
}
