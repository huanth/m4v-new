<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildPostLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
    ];

    /**
     * Get the post that was liked
     */
    public function post()
    {
        return $this->belongsTo(GuildPost::class, 'post_id');
    }

    /**
     * Get the user who liked
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}