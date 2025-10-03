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
        // Get latest posts from all guilds (limit 10)
        $latestPosts = GuildPost::with(['user', 'guild', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get some popular guilds (by member count)
        $popularGuilds = Guild::withCount('members')
            ->orderBy('members_count', 'desc')
            ->limit(6)
            ->get();

        return view('welcome', compact('latestPosts', 'popularGuilds'));
    }
}
