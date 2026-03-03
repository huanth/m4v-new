<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guild;
use App\Models\GuildPost;
use App\Models\GuildCategory;
use App\Models\GuildPostLike;
use App\Services\NotificationService;

class GuildPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    /**
     * Show create post form
     */
    public function create($id)
    {
        $guild = Guild::with(['categories'])->findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        if (!$userMembership) {
            return redirect()->back()->with('error', 'Bạn phải là thành viên của bang hội để tạo bài viết.');
        }

        return view('guilds.posts.create', compact('guild', 'userMembership'));
    }

    /**
     * Store a new post
     */
    public function store(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        if (!$userMembership) {
            return redirect()->back()->with('error', 'Bạn phải là thành viên của bang hội để tạo bài viết.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'category_id' => 'nullable|exists:guild_categories,id',
        ]);

        if ($request->category_id) {
            $category = GuildCategory::where('id', $request->category_id)
                ->where('guild_id', $guild->id)
                ->first();
            
            if (!$category) {
                return redirect()->back()->with('error', 'Danh mục không hợp lệ.');
            }
        }

        GuildPost::create([
            'guild_id' => $guild->id,
            'category_id' => $request->category_id,
            'author_id' => $user->id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('guilds.show', $guild->id)
            ->with('success', 'Tạo bài viết thành công!');
    }

    /**
     * Show post details
     */
    public function show($id, $postId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::with([
            'author', 
            'category', 
            'guild', 
            'likes'
        ])
            ->where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $comments = $post->comments()
            ->with(['user', 'parent.user'])
            ->paginate(10);

        $user = Auth::user();
        $userMembership = null;
        if ($user) {
            $userMembership = $guild->members()->where('user_id', $user->id)->first();
        }

        $post->incrementViews();

        return view('guilds.posts.show', compact('guild', 'post', 'userMembership', 'comments'));
    }

    /**
     * Show edit post form
     */
    public function edit($id, $postId)
    {
        $guild = Guild::with(['categories'])->findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $user = Auth::user();
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        if (!$post->canEdit($user->id)) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        return view('guilds.posts.edit', compact('guild', 'post', 'userMembership'));
    }

    /**
     * Update post
     */
    public function update(Request $request, $id, $postId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $user = Auth::user();
        
        if (!$post->canEdit($user->id)) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'category_id' => 'nullable|exists:guild_categories,id',
        ]);

        if ($request->category_id) {
            $category = GuildCategory::where('id', $request->category_id)
                ->where('guild_id', $guild->id)
                ->first();
            
            if (!$category) {
                return redirect()->back()->with('error', 'Danh mục không hợp lệ.');
            }
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('guilds.posts.show', [$guild->id, $post->id])
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    /**
     * Delete post
     */
    public function destroy($id, $postId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $user = Auth::user();
        
        if (!$post->canDelete($user->id)) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bài viết này.');
        }

        $post->delete();

        return redirect()->route('guilds.show', $guild->id)
            ->with('success', 'Xóa bài viết thành công!');
    }

    /**
     * Toggle post pin status
     */
    public function togglePin($id, $postId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $user = Auth::user();
        
        if (!$post->canPin($user->id)) {
            return redirect()->back()->with('error', 'Bạn không có quyền ghim bài viết này.');
        }

        $post->update(['is_pinned' => !$post->is_pinned]);

        $status = $post->is_pinned ? 'ghim' : 'bỏ ghim';
        return redirect()->back()->with('success', "Đã {$status} bài viết thành công!");
    }

    /**
     * Toggle post lock status
     */
    public function toggleLock($id, $postId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $user = Auth::user();
        
        if (!$post->canLock($user->id)) {
            return redirect()->back()->with('error', 'Bạn không có quyền khóa bài viết này.');
        }

        $post->update(['is_locked' => !$post->is_locked]);

        $status = $post->is_locked ? 'khóa' : 'mở khóa';
        return redirect()->back()->with('success', "Đã {$status} bài viết thành công!");
    }

    /**
     * Like/Unlike a post
     */
    public function toggleLike($id, $postId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $user = Auth::user();

        $existingLike = GuildPostLike::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Đã bỏ thích bài viết!';
        } else {
            GuildPostLike::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $message = 'Đã thích bài viết!';
            
            NotificationService::createPostLikeNotification($post->id, $user->id);
        }

        return redirect()->back()->with('success', $message);
    }
}
