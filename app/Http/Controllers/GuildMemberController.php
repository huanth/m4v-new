<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Guild;
use App\Models\GuildMember;
use App\Services\GuildMemberService;

class GuildMemberController extends Controller
{
    protected GuildMemberService $guildMemberService;

    public function __construct(GuildMemberService $guildMemberService)
    {
        $this->middleware('auth')->except(['index']);
        $this->guildMemberService = $guildMemberService;
    }

    /**
     * Show guild members list
     */
    public function index($id)
    {
        $guild = Guild::with(['leader', 'members.user'])->findOrFail($id);
        
        $userMembership = null;
        if (auth()->check()) {
            $userMembership = $guild->members()->where('user_id', auth()->id())->first();
        }

        $members = $guild->members()->with('user')->get();
        $membersByRole = $members->groupBy('role');

        return view('guilds.members', compact('guild', 'userMembership', 'membersByRole'));
    }

    /**
     * Join guild
     */
    public function join($id)
    {
        $guild = Guild::findOrFail($id);

        Gate::authorize('join', $guild);

        $this->guildMemberService->joinGuild($guild, auth()->id());

        return redirect()->back()->with('success', 'Gia nhập bang hội thành công!');
    }

    /**
     * Leave guild
     */
    public function leave($id)
    {
        $guild = Guild::findOrFail($id);

        Gate::authorize('leave', $guild);

        $this->guildMemberService->leaveGuild($guild, auth()->id());

        return redirect()->route('guilds.index')
            ->with('success', 'Rời bang hội thành công!');
    }

    /**
     * Update member role
     */
    public function updateRole(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);

        $request->validate([
            'member_id' => 'required|exists:guild_members,id',
            'role' => 'required|in:' . Guild::ROLE_VICE_LEADER . ',' . Guild::ROLE_ELDER . ',' . Guild::ROLE_MEMBER,
        ]);

        $member = GuildMember::where('id', $request->member_id)
            ->where('guild_id', $guild->id)
            ->firstOrFail();

        Gate::authorize('updateRole', [$guild, $member]);

        $this->guildMemberService->updateRole($member, $request->role);

        return redirect()->back()->with('success', 'Cập nhật role thành công!');
    }
}
