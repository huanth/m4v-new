<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Guild;
use App\Models\GuildMember;
use App\Models\GuildCategory;
use App\Models\GuildPost;
use App\Models\GuildPostLike;
use App\Models\GuildPostComment;
use App\Models\GuildCommentLike;
use App\Services\NotificationService;

class GuildController extends Controller
{
    public function __construct()
    {
        // Apply auth middleware only to methods that require authentication
        $this->middleware('auth')->only([
            'create', 'store', 'manage', 'join', 'leave', 'updateMemberRole',
            'createCategory', 'updateCategory', 'deleteCategory',
            'updateBanner', 'updateAnnouncement',
            'createPost', 'storePost', 'editPost', 'updatePost', 'deletePost',
            'togglePinPost', 'toggleLockPost', 'toggleLikePost',
            'addComment', 'editComment', 'deleteComment', 'toggleLikeComment'
        ]);
    }

    /**
     * Show guild creation form
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check if user can create guild
        if (!$user->canCreateGuild()) {
            return redirect()->back()->with('error', 'Bạn không có quyền tạo bang hội.');
        }

        // Check if user is already in a guild
        if ($user->isInGuild()) {
            return redirect()->back()->with('error', 'Bạn đã ở trong một bang hội rồi.');
        }

        return view('guilds.create');
    }

    /**
     * Store a new guild
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user can create guild
        if (!$user->canCreateGuild()) {
            return redirect()->back()->with('error', 'Bạn không có quyền tạo bang hội.');
        }

        // Check if user is already in a guild
        if ($user->isInGuild()) {
            return redirect()->back()->with('error', 'Bạn đã ở trong một bang hội rồi.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:guilds,name',
            'description' => 'nullable|string|max:1000',
        ]);

        // Create guild
        $guild = Guild::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $user->id,
            'max_members' => 999999, // Không giới hạn số thành viên
        ]);

        // Add creator as leader
        GuildMember::create([
            'guild_id' => $guild->id,
            'user_id' => $user->id,
            'role' => Guild::ROLE_LEADER,
        ]);

        return redirect()->route('guilds.show', $guild->id)
            ->with('success', 'Tạo bang hội thành công!');
    }

    /**
     * Show guild details
     */
    public function show($id)
    {
        $guild = Guild::with(['leader', 'categories' => function($query) {
            $query->withCount('posts');
        }])->findOrFail($id);
        $user = Auth::user();
        
        $userMembership = null;
        if ($user) {
            $userMembership = $guild->members()->where('user_id', $user->id)->first();
        }

        // Load members only if user can manage roles (for Role Management section)
        if ($userMembership && $userMembership->canManageRoles()) {
            $guild->load('members.user');
        }

        // Filter posts by category if requested
        $postsQuery = $guild->posts()->with('author');
        if (request('category')) {
            $postsQuery->where('category_id', request('category'));
        }
        $posts = $postsQuery->ordered()->get();
        
        // Add posts to guild object for view
        $guild->setRelation('posts', $posts);

        return view('guilds.show', compact('guild', 'userMembership'));
    }

    /**
     * Join guild
     */
    public function join($id)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();

        // Check if user can join
        if (!$guild->canUserJoin($user->id)) {
            if ($guild->hasMember($user->id)) {
                return redirect()->back()->with('error', 'Bạn đã là thành viên của bang hội này rồi.');
            }
            if (!$guild->is_active) {
                return redirect()->back()->with('error', 'Bang hội này đã bị vô hiệu hóa.');
            }
        }

        // Add user to guild as member
        GuildMember::create([
            'guild_id' => $guild->id,
            'user_id' => $user->id,
            'role' => Guild::ROLE_MEMBER,
        ]);

        return redirect()->back()->with('success', 'Gia nhập bang hội thành công!');
    }

    /**
     * Leave guild
     */
    public function leave($id)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();

        $membership = $guild->members()->where('user_id', $user->id)->first();
        
        if (!$membership) {
            return redirect()->back()->with('error', 'Bạn không phải là thành viên của bang hội này.');
        }

        // Leader cannot leave guild
        if ($membership->isLeader()) {
            return redirect()->back()->with('error', 'Bang chủ không thể rời bang hội. Hãy chuyển quyền bang chủ trước.');
        }

        $membership->delete();

        return redirect()->route('guilds.index')
            ->with('success', 'Rời bang hội thành công!');
    }

    /**
     * Show all guilds
     */
    public function index()
    {
        $guilds = Guild::with(['leader', 'members'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('guilds.index', compact('guilds'));
    }

    /**
     * Update member role
     */
    public function updateMemberRole(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        // Check if user has management permissions
        // Super Admin and Admin have full access to all guilds
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        // Or if user is a member with management permissions
        if (!$canManage && (!$userMembership || !$userMembership->canManageRoles())) {
            return redirect()->back()->with('error', 'Bạn không có quyền quản lý role.');
        }

        $request->validate([
            'member_id' => 'required|exists:guild_members,id',
            'role' => 'required|in:' . Guild::ROLE_VICE_LEADER . ',' . Guild::ROLE_ELDER . ',' . Guild::ROLE_MEMBER,
        ]);

        $member = GuildMember::where('id', $request->member_id)
            ->where('guild_id', $guild->id)
            ->first();

        if (!$member) {
            return redirect()->back()->with('error', 'Thành viên không tồn tại.');
        }

        // Cannot change leader role
        if ($member->isLeader()) {
            return redirect()->back()->with('error', 'Không thể thay đổi role của bang chủ.');
        }

        $member->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'Cập nhật role thành công!');
    }

    /**
     * Create category
     */
    public function createCategory(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        // Check if user has management permissions
        // Super Admin and Admin have full access to all guilds
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        // Or if user is a member with management permissions
        if (!$canManage && (!$userMembership || !$userMembership->canCreateCategories())) {
            return redirect()->back()->with('error', 'Bạn không có quyền tạo danh mục.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        GuildCategory::create([
            'guild_id' => $guild->id,
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $guild->categories()->count(),
        ]);

        return redirect()->back()->with('success', 'Tạo danh mục thành công!');
    }

    /**
     * Update category
     */
    public function updateCategory(Request $request, $id, $categoryId)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        // Check if user has management permissions
        // Super Admin and Admin have full access to all guilds
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        // Or if user is a member with management permissions
        if (!$canManage && (!$userMembership || !$userMembership->canCreateCategories())) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa danh mục.');
        }

        $category = GuildCategory::where('id', $categoryId)
            ->where('guild_id', $guild->id)
            ->first();

        if (!$category) {
            return redirect()->back()->with('error', 'Danh mục không tồn tại.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Delete category
     */
    public function deleteCategory($id, $categoryId)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        // Check if user has management permissions
        // Super Admin and Admin have full access to all guilds
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        // Or if user is a member with management permissions
        if (!$canManage && (!$userMembership || !$userMembership->canCreateCategories())) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa danh mục.');
        }

        $category = GuildCategory::where('id', $categoryId)
            ->where('guild_id', $guild->id)
            ->first();

        if (!$category) {
            return redirect()->back()->with('error', 'Danh mục không tồn tại.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Xóa danh mục thành công!');
    }

    /**
     * Show create post form
     */
    public function createPost($id)
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
    public function storePost(Request $request, $id)
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

        // Verify category belongs to this guild
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
    public function showPost($id, $postId)
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

        // Load comments with pagination
        $comments = $post->comments()
            ->with(['user', 'parent.user'])
            ->paginate(10);

        $user = Auth::user();
        $userMembership = null;
        if ($user) {
            $userMembership = $guild->members()->where('user_id', $user->id)->first();
        }

        // Increment views count
        $post->incrementViews();

        return view('guilds.posts.show', compact('guild', 'post', 'userMembership', 'comments'));
    }

    /**
     * Show edit post form
     */
    public function editPost($id, $postId)
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
    public function updatePost(Request $request, $id, $postId)
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

        // Verify category belongs to this guild
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
    public function deletePost($id, $postId)
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
    public function togglePinPost($id, $postId)
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
    public function toggleLockPost($id, $postId)
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
    public function toggleLikePost($id, $postId)
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
            
            // Create notification for post like
            NotificationService::createPostLikeNotification($post->id, $user->id);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Add comment to a post
     */
    public function addComment(Request $request, $id, $postId)
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

        // If parent_id is provided, verify it belongs to the same post
        if ($request->parent_id) {
            $parentComment = GuildPostComment::where('id', $request->parent_id)
                ->where('post_id', $post->id)
                ->first();
            
            if (!$parentComment) {
                return redirect()->back()->with('error', 'Bình luận gốc không hợp lệ.');
            }
            
            // If parent comment already has quoted_content, don't allow quoting
            if ($parentComment->quoted_content && $request->has('include_quote')) {
                return redirect()->back()->with('error', 'Không thể trích dẫn bình luận đã có trích dẫn.');
            }
        }

        // Handle quoted content
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

        // Create notification for post comment
        NotificationService::createPostCommentNotification($post->id, $user->id);

        // If this is a reply, create notification for the parent comment author
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
    public function editComment(Request $request, $id, $postId, $commentId)
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
    public function deleteComment($id, $postId, $commentId)
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
    public function toggleLikeComment($id, $postId, $commentId)
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
            
            // Create notification for comment like
            NotificationService::createCommentLikeNotification($comment->id, $user->id);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Show guild members list
     */
    public function showMembers($id)
    {
        $guild = Guild::with(['leader', 'members.user'])->findOrFail($id);
        $user = Auth::user();
        
        $userMembership = null;
        if ($user) {
            $userMembership = $guild->members()->where('user_id', $user->id)->first();
        }

        // Get all members with their roles
        $members = $guild->members()->with('user')->get();
        
        // Group members by role
        $membersByRole = $members->groupBy('role');

        return view('guilds.members', compact('guild', 'userMembership', 'membersByRole'));
    }

    /**
     * Show guild management page
     */
    public function manage($id)
    {
        $guild = Guild::with(['leader', 'members.user', 'categories' => function($query) {
            $query->withCount('posts');
        }])->findOrFail($id);
        $user = Auth::user();
        
        $userMembership = null;
        if ($user) {
            $userMembership = $guild->members()->where('user_id', $user->id)->first();
        }

        // Check if user has management permissions
        // Super Admin and Admin have full access to all guilds
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        // Or if user is a member with management permissions
        if (!$canManage && (!$userMembership || !$userMembership->canManageRoles())) {
            return redirect()->route('guilds.show', $id)->with('error', 'Bạn không có quyền quản lý bang hội này.');
        }

        return view('guilds.manage', compact('guild', 'userMembership'));
    }

    /**
     * Update guild banner
     */
    public function updateBanner(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();

        // Check if user has management permissions
        // Super Admin and Admin have full access to all guilds
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        // Or if user is a member with management permissions
        if (!$canManage && (!$userMembership || !$userMembership->canManageRoles())) {
            return redirect()->back()->with('error', 'Bạn không có quyền quản lý bang hội này.');
        }

        $request->validate([
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->has('remove_banner')) {
            // Remove banner
            if ($guild->banner) {
                Storage::delete($guild->banner);
            }
            $guild->update(['banner' => null]);
        } elseif ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($guild->banner) {
                Storage::delete($guild->banner);
            }

            // Store new banner
            $bannerPath = $request->file('banner')->store('guilds/banners', 'public');
            $guild->update(['banner' => $bannerPath]);
        }

        return redirect()->back()->with('success', 'Banner bang hội đã được cập nhật.');
    }

    /**
     * Update guild announcement
     */
    public function updateAnnouncement(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();

        // Check if user has management permissions
        // Super Admin and Admin have full access to all guilds
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        // Or if user is a member with management permissions
        if (!$canManage && (!$userMembership || !$userMembership->canManageRoles())) {
            return redirect()->back()->with('error', 'Bạn không có quyền quản lý bang hội này.');
        }

        $request->validate([
            'announcement' => 'nullable|string|max:1000'
        ]);

        if ($request->has('clear_announcement')) {
            $guild->update(['announcement' => null]);
        } else {
            $guild->update(['announcement' => $request->announcement]);
        }

        return redirect()->back()->with('success', 'Thông báo bang hội đã được cập nhật.');
    }
}
