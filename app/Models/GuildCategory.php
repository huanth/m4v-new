<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'guild_id',
        'name',
        'description',
        'sort_order',
    ];

    /**
     * Get the guild that owns the category
     */
    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }

    /**
     * Get all posts in this category
     */
    public function posts()
    {
        return $this->hasMany(GuildPost::class, 'category_id')->ordered();
    }

    /**
     * Get posts count in this category
     */
    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }

    /**
     * Scope to load posts count
     */
    public function scopeWithPostsCount($query)
    {
        return $query->withCount('posts');
    }

    /**
     * Scope to order categories by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}