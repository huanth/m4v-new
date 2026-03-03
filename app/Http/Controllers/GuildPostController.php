<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Guild;
use App\Models\GuildPost;
use App\Models\GuildCategory;
use App\Services\GuildPostService;

class GuildPostController extends Controller
{
    protected GuildPostService $guildPostService;

    public function __construct(GuildPostService $guildPostService)
    {
        $this->middleware('auth')->except(['show']);
        $this->guildPostService = $guildPostService;
    }

    /**
     * Show create post form
     */
    public function create($id)
    {
        $guild = Guild::with(['categories'])->findOrFail($id);
        
        Gate::authorize('create', [GuildPost::class, $guild]);

        $userMembership = $guild->members()->where('user_id', auth()->id())->first();

        return view('guilds.posts.create', compact('guild', 'userMembership'));
    }

    /**
     * Store a new post
     */
    public function store(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);
        
        Gate::authorize('create', [GuildPost::class, $guild]);

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

        $this->guildPostService->createPost($guild->id, auth()->id(), $request->all());

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

        $userMembership = null;
        if (auth()->check()) {
            $userMembership = $guild->members()->where('user_id', auth()->id())->first();
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

        Gate::authorize('update', $post);

        $userMembership = $guild->members()->where('user_id', auth()->id())->first();

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

        Gate::authorize('update', $post);

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

        $this->guildPostService->updatePost($post, $request->all());

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

        Gate::authorize('delete', $post);

        $this->guildPostService->deletePost($post);

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

        Gate::authorize('pin', $post);

        $this->guildPostService->togglePin($post);

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

        Gate::authorize('lock', $post);

        $this->guildPostService->toggleLock($post);

        $status = $post->is_pinned ? 'khóa' : 'mở khóa';
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

        $liked = $this->guildPostService->toggleLike($post, auth()->id());

        $message = $liked ? 'Đã thích bài viết!' : 'Đã bỏ thích bài viết!';
        return redirect()->back()->with('success', $message);
    }
}
