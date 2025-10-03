<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildCommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
    ];

    /**
     * Get the comment that was liked
     */
    public function comment()
    {
        return $this->belongsTo(GuildPostComment::class, 'comment_id');
    }

    /**
     * Get the user who liked
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}