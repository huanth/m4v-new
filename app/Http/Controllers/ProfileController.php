<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Only require auth for index (personal profile)
        $this->middleware('auth')->only(['index']);
    }

    public function index()
    {
        $user = Auth::user();

        $stats = [
            'posts_count'    => $user->posts()->count(),
            'comments_count' => $user->comments()->count(),
            'likes_received' => \App\Models\GuildPostLike::whereIn('post_id', $user->posts()->pluck('id'))->count()
                              + \App\Models\GuildCommentLike::whereIn('comment_id', $user->comments()->pluck('id'))->count(),
            'likes_given'    => $user->postLikes()->count() + $user->commentLikes()->count(),
        ];

        return view('profile', compact('user', 'stats'));
    }

    /**
     * Show public user profile
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        $banHistory = $user->getBanHistory();
        $activeBan = $user->getActiveBan();

        // Thống kê hoạt động thực tế
        $stats = [
            'posts_count'    => $user->posts()->count(),
            'comments_count' => $user->comments()->count(),
            'likes_received' => \App\Models\GuildPostLike::whereIn('post_id', $user->posts()->pluck('id'))->count()
                              + \App\Models\GuildCommentLike::whereIn('comment_id', $user->comments()->pluck('id'))->count(),
            'likes_given'    => $user->postLikes()->count() + $user->commentLikes()->count(),
        ];

        // Hoạt động gần đây: gộp 5 bài viết + 5 bình luận mới nhất, sort theo thời gian
        $recentPosts = $user->posts()
            ->with('guild')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'type'       => 'post',
                'content'    => $p->title,
                'url'        => route('guilds.posts.show', [$p->guild_id, $p->id]),
                'context'    => $p->guild->name ?? '—',
                'created_at' => $p->created_at,
            ]);

        $recentComments = $user->comments()
            ->with('post.guild')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'type'       => 'comment',
                'content'    => \Illuminate\Support\Str::limit(strip_tags($c->content), 100),
                'url'        => route('guilds.posts.show', [$c->post->guild_id ?? 0, $c->post_id]) . '#comment-' . $c->id,
                'context'    => $c->post->title ?? '—',
                'created_at' => $c->created_at,
            ]);

        $recentActivity = $recentPosts->concat($recentComments)
            ->sortByDesc('created_at')
            ->take(8)
            ->values();

        return view('user.profile', compact(
            'user', 'currentUser', 'banHistory', 'activeBan', 'stats', 'recentActivity'
        ));
    }

    /**
     * Update user avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::exists($user->avatar)) {
            Storage::delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        $user->update(['avatar' => $path]);

        return redirect()->route('profile')->with('success', 'Ảnh đại diện đã được cập nhật thành công!');
    }

    /**
     * Remove user avatar
     */
    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::exists($user->avatar)) {
            Storage::delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return redirect()->route('profile')->with('success', 'Ảnh đại diện đã được xóa thành công!');
    }

    /**
     * Show user notifications page
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = [];
        $clearedNotifications = session('cleared_notifications', []);
        
        // Add ban notification if user is banned and not cleared
        if ($user->isBanned() && !in_array('ban', $clearedNotifications) && !in_array('all', $clearedNotifications)) {
            $activeBan = $user->getActiveBan();
            $notifications[] = [
                'type' => 'ban',
                'title' => 'Bạn đã bị ban',
                'message' => 'Tài khoản của bạn đã bị ban. Lý do: ' . $activeBan->reason,
                'time' => $activeBan->banned_at,
                'action_url' => route('user.ban.history', $user),
                'action_text' => 'Xem chi tiết',
                'icon' => 'ban',
                'color' => 'red'
            ];
        }
        
        return view('notifications.index', compact('user', 'notifications'));
    }

    /**
     * Clear a specific notification
     */
    public function clearNotification(Request $request)
    {
        $user = Auth::user();
        $type = $request->input('type');
        
        // For now, we'll use session to track cleared notifications
        $clearedNotifications = session('cleared_notifications', []);
        $clearedNotifications[] = $type;
        session(['cleared_notifications' => $clearedNotifications]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Clear all notifications
     */
    public function clearAllNotifications()
    {
        $user = Auth::user();
        
        // Clear all notification types
        session(['cleared_notifications' => ['ban', 'all']]);
        
        return response()->json(['success' => true]);
    }
}