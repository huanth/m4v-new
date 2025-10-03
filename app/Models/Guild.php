<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guild extends Model
{
    protected $fillable = [
        'name',
        'description',
        'logo',
        'banner',
        'announcement',
        'leader_id',
        'max_members',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_members' => 'integer',
    ];

    // Role constants
    const ROLE_LEADER = 'leader';
    const ROLE_VICE_LEADER = 'vice_leader';
    const ROLE_ELDER = 'elder';
    const ROLE_MEMBER = 'member';

    /**
     * Get the leader of the guild
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Get all members of the guild
     */
    public function members(): HasMany
    {
        return $this->hasMany(GuildMember::class);
    }

    /**
     * Get all users in the guild
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'guild_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get all categories of the guild
     */
    public function categories(): HasMany
    {
        return $this->hasMany(GuildCategory::class)->ordered();
    }

    /**
     * Get all posts of the guild
     */
    public function posts(): HasMany
    {
        return $this->hasMany(GuildPost::class)->ordered();
    }

    /**
     * Get current member count
     */
    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    /**
     * Check if guild is full (no limit)
     */
    public function isFull()
    {
        return false; // Không giới hạn số thành viên
    }

    /**
     * Check if user is a member of this guild
     */
    public function hasMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    /**
     * Get member role in guild
     */
    public function getMemberRole($userId)
    {
        $member = $this->members()->where('user_id', $userId)->first();
        return $member ? $member->role : null;
    }

    /**
     * Get role display name
     */
    public static function getRoleDisplayName($role)
    {
        $roleNames = [
            self::ROLE_LEADER => 'Bang chủ',
            self::ROLE_VICE_LEADER => 'Phó bang',
            self::ROLE_ELDER => 'Trưởng lão',
            self::ROLE_MEMBER => 'Thành viên',
        ];

        return $roleNames[$role] ?? 'Unknown';
    }

    /**
     * Check if user can join guild
     */
    public function canUserJoin($userId)
    {
        // Check if user is already a member
        if ($this->hasMember($userId)) {
            return false;
        }

        // Check if guild is active
        if (!$this->is_active) {
            return false;
        }

        return true; // Không giới hạn số thành viên
    }
}
