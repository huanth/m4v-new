<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guild;
use App\Models\GuildMember;

class GuildController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store']);
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
     * Show guild creation form
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->canCreateGuild()) {
            return redirect()->back()->with('error', 'Bạn không có quyền tạo bang hội.');
        }

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
        
        if (!$user->canCreateGuild()) {
            return redirect()->back()->with('error', 'Bạn không có quyền tạo bang hội.');
        }

        if ($user->isInGuild()) {
            return redirect()->back()->with('error', 'Bạn đã ở trong một bang hội rồi.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:guilds,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $guild = Guild::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $user->id,
            'max_members' => 999999,
        ]);

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

        if ($userMembership && $userMembership->canManageRoles()) {
            $guild->load('members.user');
        }

        $postsQuery = $guild->posts()->with('author');
        if (request('category')) {
            $postsQuery->where('category_id', request('category'));
        }
        $posts = $postsQuery->ordered()->get();
        
        $guild->setRelation('posts', $posts);

        return view('guilds.show', compact('guild', 'userMembership'));
    }
}
