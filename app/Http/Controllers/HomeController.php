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
        // Get latest posts from all guilds, prioritize by activity (likes + comments)
        $latestPosts = GuildPost::with(['user', 'guild', 'category'])
            ->selectRaw('guild_posts.*, 
                (SELECT COUNT(*) FROM guild_post_likes WHERE guild_post_likes.post_id = guild_posts.id) as likes_count,
                (SELECT COUNT(*) FROM guild_post_comments WHERE guild_post_comments.post_id = guild_posts.id) as comments_count')
            ->orderByRaw('(likes_count + comments_count) DESC, created_at DESC')
            ->limit(10)
            ->get();

        // Get some popular guilds (by member count)
        $popularGuilds = Guild::withCount('members')
            ->orderBy('members_count', 'desc')
            ->limit(6)
            ->get();

        // Debug: Log the first post's activity score
        if ($latestPosts->count() > 0) {
            $firstPost = $latestPosts->first();
            \Log::info('Home page - First post activity', [
                'post_id' => $firstPost->id,
                'title' => $firstPost->title,
                'likes_count' => $firstPost->likes_count,
                'comments_count' => $firstPost->comments_count,
                'total_activity' => $firstPost->likes_count + $firstPost->comments_count,
                'created_at' => $firstPost->created_at
            ]);
        }

        return view('welcome', compact('latestPosts', 'popularGuilds'));
    }
}
