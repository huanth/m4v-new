<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuildMember extends Model
{
    protected $fillable = [
        'guild_id',
        'user_id',
        'role',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * Get the guild this member belongs to
     */
    public function guild(): BelongsTo
    {
        return $this->belongsTo(Guild::class);
    }

    /**
     * Get the user this member represents
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayNameAttribute()
    {
        return Guild::getRoleDisplayName($this->role);
    }

    /**
     * Check if member is leader
     */
    public function isLeader()
    {
        return $this->role === Guild::ROLE_LEADER;
    }

    /**
     * Check if member is vice leader
     */
    public function isViceLeader()
    {
        return $this->role === Guild::ROLE_VICE_LEADER;
    }

    /**
     * Check if member is elder
     */
    public function isElder()
    {
        return $this->role === Guild::ROLE_ELDER;
    }

    /**
     * Check if member is regular member
     */
    public function isMember()
    {
        return $this->role === Guild::ROLE_MEMBER;
    }

    /**
     * Check if member has admin privileges
     */
    public function isAdmin()
    {
        return in_array($this->role, [Guild::ROLE_LEADER, Guild::ROLE_VICE_LEADER]);
    }

    /**
     * Check if member can manage roles
     */
    public function canManageRoles()
    {
        return $this->role === Guild::ROLE_LEADER;
    }

    /**
     * Check if member can create categories
     */
    public function canCreateCategories()
    {
        return in_array($this->role, [Guild::ROLE_LEADER, Guild::ROLE_VICE_LEADER]);
    }

    /**
     * Get role hierarchy level (higher number = higher rank)
     */
    public function getRoleLevel()
    {
        $levels = [
            Guild::ROLE_MEMBER => 1,
            Guild::ROLE_ELDER => 2,
            Guild::ROLE_VICE_LEADER => 3,
            Guild::ROLE_LEADER => 4,
        ];

        return $levels[$this->role] ?? 0;
    }
}
