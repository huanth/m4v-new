<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Guild;
use App\Models\GuildPost;
use App\Models\GuildPostComment;
use App\Services\GuildCommentService;

class GuildCommentController extends Controller
{
    protected GuildCommentService $guildCommentService;

    public function __construct(GuildCommentService $guildCommentService)
    {
        $this->middleware('auth');
        $this->guildCommentService = $guildCommentService;
    }

    /**
     * Add comment to a post
     */
    public function store(Request $request, $id, $postId)
    {
        $guild = Guild::findOrFail($id);
        
        Gate::authorize('create', [GuildPostComment::class, $guild]);

        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $request->validate([
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:guild_post_comments,id',
            'quoted_content' => 'nullable|string|max:500',
        ]);

        if ($request->parent_id) {
            $parentComment = GuildPostComment::where('id', $request->parent_id)
                ->where('post_id', $post->id)
                ->first();
            
            if (!$parentComment) {
                return redirect()->back()->with('error', 'Bình luận gốc không hợp lệ.');
            }
        }

        $this->guildCommentService->createComment($post->id, auth()->id(), $request->all());

        $message = $request->parent_id ? 'Đã trả lời bình luận!' : 'Đã thêm bình luận!';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Edit comment
     */
    public function update(Request $request, $id, $postId, $commentId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $comment = GuildPostComment::where('id', $commentId)
            ->where('post_id', $post->id)
            ->firstOrFail();

        Gate::authorize('update', $comment);

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $this->guildCommentService->updateComment($comment, $request->all());

        return redirect()->back()->with('success', 'Đã cập nhật bình luận!');
    }

    /**
     * Delete comment
     */
    public function destroy($id, $postId, $commentId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $comment = GuildPostComment::where('id', $commentId)
            ->where('post_id', $post->id)
            ->firstOrFail();

        Gate::authorize('delete', $comment);

        $this->guildCommentService->deleteComment($comment);

        return redirect()->back()->with('success', 'Đã xóa bình luận!');
    }

    /**
     * Like/Unlike a comment
     */
    public function toggleLike($id, $postId, $commentId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $comment = GuildPostComment::where('id', $commentId)
            ->where('post_id', $post->id)
            ->firstOrFail();

        $liked = $this->guildCommentService->toggleLike($comment, auth()->id());

        $message = $liked ? 'Đã thích bình luận!' : 'Đã bỏ thích bình luận!';
        return redirect()->back()->with('success', $message);
    }
}
