<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuildBan extends Model
{
    protected $fillable = [
        'guild_id',
        'user_id',
        'banned_by',
        'reason',
        'banned_at',
        'is_active',
    ];

    protected $casts = [
        'banned_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function guild(): BelongsTo
    {
        return $this->belongsTo(Guild::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function isUserBanned(int $guildId, int $userId): bool
    {
        return self::where('guild_id', $guildId)
            ->where('user_id', $userId)
            ->active()
            ->exists();
    }
}
