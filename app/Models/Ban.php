<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class Ban extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'banned_by',
        'reason',
        'description',
        'banned_at',
        'expires_at',
        'is_permanent',
        'is_active',
        'ban_type',
        'duration',
    ];

    protected $casts = [
        'banned_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_permanent' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->where('is_permanent', true)
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeExpired($query)
    {
        return $query->where('is_active', true)
                    ->where('is_permanent', false)
                    ->where('expires_at', '<=', now());
    }

    // Methods
    public function isExpired()
    {
        return !$this->is_permanent && $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive()
    {
        return $this->is_active && ($this->is_permanent || !$this->isExpired());
    }

    public function getDurationAttribute()
    {
        if ($this->is_permanent) {
            return 'Vĩnh viễn';
        }

        if (!$this->expires_at) {
            return 'Không xác định';
        }

        // If we have a stored duration string, use it
        if ($this->attributes['duration']) {
            return $this->attributes['duration'];
        }

        // Fallback: Calculate the actual duration from banned_at to expires_at
        $start = $this->banned_at;
        $end = $this->expires_at;
        
        $diff = $start->diff($end);
        
        // Build duration string based on actual time components
        $parts = [];
        
        if ($diff->y > 0) {
            $parts[] = $diff->y . ' năm';
        }
        if ($diff->m > 0) {
            $parts[] = $diff->m . ' tháng';
        }
        if ($diff->d > 0) {
            $parts[] = $diff->d . ' ngày';
        }
        if ($diff->h > 0) {
            $parts[] = $diff->h . ' giờ';
        }
        if ($diff->i > 0) {
            $parts[] = $diff->i . ' phút';
        }
        
        // If no parts, it means less than a minute
        if (empty($parts)) {
            return 'Dưới 1 phút';
        }
        
        // Join parts with comma, but limit to first 2 parts for readability
        return implode(', ', array_slice($parts, 0, 2));
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'Đã hủy';
        }

        if ($this->isExpired()) {
            return 'Đã hết hạn';
        }

        if ($this->is_permanent) {
            return 'Vĩnh viễn';
        }

        return 'Đang hiệu lực';
    }

    // Static methods
    public static function banUser($userId, $bannedBy, $reason, $description = null, $duration = null, $banType = 'normal')
    {
        $bannedAt = now();
        $expiresAt = null;
        $isPermanent = false;

        if ($duration) {
            $expiresAt = $bannedAt->copy()->add($duration);
        } else {
            $isPermanent = true;
        }

        return self::create([
            'user_id' => $userId,
            'banned_by' => $bannedBy,
            'reason' => $reason,
            'description' => $description,
            'banned_at' => $bannedAt,
            'expires_at' => $expiresAt,
            'is_permanent' => $isPermanent,
            'ban_type' => $banType,
        ]);
    }

    public static function unbanUser($userId)
    {
        return self::where('user_id', $userId)
                  ->where('is_active', true)
                  ->update(['is_active' => false]);
    }

    public static function getActiveBan($userId)
    {
        return self::where('user_id', $userId)
                  ->active()
                  ->first();
    }

    public static function getUserBanHistory($userId)
    {
        return self::where('user_id', $userId)
                  ->with('bannedBy')
                  ->orderBy('banned_at', 'desc')
                  ->get();
    }
}