<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guild;
use App\Models\GuildMember;

class GuildMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Show guild members list
     */
    public function index($id)
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
     * Update member role
     */
    public function updateRole(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
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
}
