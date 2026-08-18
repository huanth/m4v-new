<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_note',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    const TYPE_POST = 'post';
    const TYPE_COMMENT = 'comment';
    const TYPE_USER = 'user';

    const STATUS_PENDING = 'pending';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_DISMISSED = 'dismissed';

    /**
     * Map of reportable_type => model class.
     * Kept explicit (instead of Eloquent morphTo) so an attacker can never
     * point reportable_type at an arbitrary class.
     */
    public const REPORTABLE_MODELS = [
        self::TYPE_POST => GuildPost::class,
        self::TYPE_COMMENT => GuildPostComment::class,
        self::TYPE_USER => User::class,
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Resolve the reported entity (post, comment, or user).
     */
    public function getReportableAttribute()
    {
        $modelClass = self::REPORTABLE_MODELS[$this->reportable_type] ?? null;

        return $modelClass ? $modelClass::find($this->reportable_id) : null;
    }
}
