<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guild;
use App\Models\GuildPost;
use App\Models\GuildPostComment;
use App\Models\GuildCommentLike;
use App\Services\NotificationService;

class GuildCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Add comment to a post
     */
    public function store(Request $request, $id, $postId)
    {
        $guild = Guild::findOrFail($id);
        $post = GuildPost::where('id', $postId)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        $user = Auth::user();

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

        $quotedContent = null;
        if ($request->parent_id && $request->has('include_quote') && $request->quoted_content) {
            $quotedContent = $request->quoted_content;
        }

        $comment = GuildPostComment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'quoted_content' => $quotedContent,
            'content' => $request->content,
        ]);

        \Cache::forget("post_activity_{$post->id}");

        NotificationService::createPostCommentNotification($post->id, $user->id);

        if ($request->parent_id) {
            $parentComment = GuildPostComment::find($request->parent_id);
            if ($parentComment && $parentComment->user_id != $user->id) {
                NotificationService::createCommentReplyNotification($comment->id, $parentComment->user_id);
            }
        }

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

        $user = Auth::user();
        
        if (!$comment->canEdit($user->id)) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa bình luận này.');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->update(['content' => $request->content]);

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

        $user = Auth::user();
        
        if (!$comment->canDelete($user->id)) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này.');
        }

        $comment->delete();

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

        $user = Auth::user();

        $existingLike = GuildCommentLike::where('comment_id', $comment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Đã bỏ thích bình luận!';
        } else {
            GuildCommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => $user->id,
            ]);
            $message = 'Đã thích bình luận!';
            
            NotificationService::createCommentLikeNotification($comment->id, $user->id);
        }

        return redirect()->back()->with('success', $message);
    }
}
