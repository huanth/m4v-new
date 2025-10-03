<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuildPost;
use App\Models\Guild;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get latest posts from all guilds, prioritize by recency and activity
        $latestPosts = GuildPost::with(['user', 'guild', 'category'])
            ->selectRaw('guild_posts.*, 
                (SELECT COUNT(*) FROM guild_post_likes WHERE guild_post_likes.post_id = guild_posts.id) as likes_count,
                (SELECT COUNT(*) FROM guild_post_comments WHERE guild_post_comments.post_id = guild_posts.id) as comments_count,
                TIMESTAMPDIFF(HOUR, guild_posts.created_at, NOW()) as hours_ago')
            ->orderByRaw('
                CASE 
                    WHEN (likes_count + comments_count) > 0 AND TIMESTAMPDIFF(HOUR, created_at, NOW()) <= 24 THEN 1
                    WHEN TIMESTAMPDIFF(HOUR, created_at, NOW()) <= 2 THEN 2
                    WHEN (likes_count + comments_count) > 0 THEN 3
                    ELSE 4
                END,
                (likes_count + comments_count) DESC,
                created_at DESC
            ')
            ->limit(10)
            ->get();

        // Get some popular guilds (by member count)
        $popularGuilds = Guild::withCount('members')
            ->orderBy('members_count', 'desc')
            ->limit(6)
            ->get();

        // Debug: Log the first few posts' activity scores
        if ($latestPosts->count() > 0) {
            $topPosts = $latestPosts->take(3);
            \Log::info('Home page - Top posts ranking', [
                'total_posts' => $latestPosts->count(),
                'top_posts' => $topPosts->map(function($post) {
                    return [
                        'post_id' => $post->id,
                        'title' => substr($post->title, 0, 50) . '...',
                        'likes_count' => $post->likes_count,
                        'comments_count' => $post->comments_count,
                        'total_activity' => $post->likes_count + $post->comments_count,
                        'hours_ago' => $post->hours_ago,
                        'created_at' => $post->created_at->format('Y-m-d H:i:s')
                    ];
                })->toArray()
            ]);
        }

        return view('welcome', compact('latestPosts', 'popularGuilds'));
    }
}
