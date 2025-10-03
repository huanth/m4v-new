<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Ban;
use App\Models\Message;
use App\Models\Guild;
use App\Models\GuildMember;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the name attribute (alias for username for compatibility)
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->username;
    }

    // Role constants
    const ROLE_SUPER_ADMIN = 'SADMIN';
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_SUPER_MOD = 'SMod';
    const ROLE_FORUM_MOD = 'FMod';
    const ROLE_USER = 'user';

    // Role hierarchy
    const ROLE_HIERARCHY = [
        self::ROLE_SUPER_ADMIN => 5,
        self::ROLE_ADMIN => 4,
        self::ROLE_SUPER_MOD => 3,
        self::ROLE_FORUM_MOD => 2,
        self::ROLE_USER => 1,
    ];

    /**
     * Check if user has a specific role or higher
     */
    public function hasRole($role)
    {
        return isset(self::ROLE_HIERARCHY[$this->role]) && 
               isset(self::ROLE_HIERARCHY[$role]) &&
               self::ROLE_HIERARCHY[$this->role] >= self::ROLE_HIERARCHY[$role];
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is admin or higher
     */
    public function isAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    /**
     * Check if user is moderator or higher
     */
    public function isModerator()
    {
        return $this->hasRole(self::ROLE_FORUM_MOD);
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName()
    {
        $roleNames = [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_SUPER_MOD => 'Super Moderator',
            self::ROLE_FORUM_MOD => 'Forum Moderator',
            self::ROLE_USER => 'User',
        ];

        return $roleNames[$this->role] ?? 'Unknown';
    }

    /**
     * Check if user can ban another user
     */
    public function canBan($targetUser)
    {
        // Super Admin can ban everyone
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Admin can ban SMod, FMod, and user
        if ($this->isAdmin() && !$targetUser->isSuperAdmin() && !$targetUser->isAdmin()) {
            return true;
        }

        // SMod can ban FMod and user
        if ($this->role === self::ROLE_SUPER_MOD && 
            ($targetUser->role === self::ROLE_FORUM_MOD || $targetUser->role === self::ROLE_USER)) {
            return true;
        }

        // FMod can ban user only
        if ($this->role === self::ROLE_FORUM_MOD && $targetUser->role === self::ROLE_USER) {
            return true;
        }

        return false;
    }

    /**
     * Check if user is currently banned
     */
    public function isBanned()
    {
        return Ban::getActiveBan($this->id) !== null;
    }

    /**
     * Get active ban for user
     */
    public function getActiveBan()
    {
        return Ban::getActiveBan($this->id);
    }

    /**
     * Get ban history for user
     */
    public function getBanHistory()
    {
        return Ban::getUserBanHistory($this->id);
    }

    /**
     * Check if user can comment (not banned from commenting)
     */
    public function canComment()
    {
        $ban = $this->getActiveBan();
        if (!$ban) {
            return true;
        }

        // Normal ban and super ban both prevent commenting
        return !in_array($ban->ban_type, ['normal', 'super']);
    }

    /**
     * Check if user can post (not banned from posting)
     */
    public function canPost()
    {
        $ban = $this->getActiveBan();
        if (!$ban) {
            return true;
        }

        // Normal ban and super ban both prevent posting
        return !in_array($ban->ban_type, ['normal', 'super']);
    }

    /**
     * Check if user can login (not banned from login)
     */
    public function canLogin()
    {
        $ban = $this->getActiveBan();
        if (!$ban) {
            return true;
        }

        // Only super ban prevents login
        return $ban->ban_type !== 'super';
    }

    /**
     * Get messages sent by this user
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get all conversations for this user
     */
    public function conversations()
    {
        return Message::where('sender_id', $this->id)
            ->orWhere('receiver_id', $this->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) {
                return $message->sender_id == $this->id ? $message->receiver_id : $message->sender_id;
            });
    }

    /**
     * Get unread messages count
     */
    public function getUnreadMessagesCount()
    {
        return Message::unreadFor($this->id)->count();
    }

    /**
     * Get count of users who have sent unread messages to this user
     */
    public function getUnreadConversationsCount()
    {
        return Message::unreadFor($this->id)
            ->distinct('sender_id')
            ->count('sender_id');
    }

    /**
     * Get guilds created by this user
     */
    public function createdGuilds()
    {
        return $this->hasMany(Guild::class, 'leader_id');
    }

    /**
     * Get guild memberships
     */
    public function guildMemberships()
    {
        return $this->hasMany(GuildMember::class);
    }

    /**
     * Get guilds this user belongs to
     */
    public function guilds()
    {
        return $this->belongsToMany(Guild::class, 'guild_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get current guild membership
     */
    public function getCurrentGuild()
    {
        return $this->guildMemberships()->with('guild')->first();
    }

    /**
     * Check if user is in any guild
     */
    public function isInGuild()
    {
        return $this->guildMemberships()->exists();
    }

    /**
     * Check if user can create guild
     */
    public function canCreateGuild()
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    /**
     * Get all notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Get unread notifications
     */
    public function getUnreadNotifications()
    {
        return $this->notifications()->unread()->with('fromUser')->latest()->get();
    }
}
