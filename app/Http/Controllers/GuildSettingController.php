<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Guild;
use App\Services\GuildService;

class GuildSettingController extends Controller
{
    protected GuildService $guildService;

    public function __construct(GuildService $guildService)
    {
        $this->middleware('auth');
        $this->guildService = $guildService;
    }

    /**
     * Show guild management page
     */
    public function edit($id)
    {
        $guild = Guild::with(['leader', 'members.user', 'categories' => function($query) {
            $query->withCount('posts');
        }])->findOrFail($id);

        Gate::authorize('manage', $guild);
        
        $userMembership = $guild->members()->where('user_id', auth()->id())->first();

        return view('guilds.manage', compact('guild', 'userMembership'));
    }

    /**
     * Update guild banner
     */
    public function updateBanner(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);

        Gate::authorize('updateBanner', $guild);

        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('banner')) {
            $this->guildService->updateBanner($guild, $request->file('banner'));
            return redirect()->back()->with('success', 'Cập nhật ảnh bìa thành công!');
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải ảnh lên.');
    }

    /**
     * Update guild announcement
     */
    public function updateAnnouncement(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);

        Gate::authorize('updateAnnouncement', $guild);

        $request->validate([
            'announcement' => 'nullable|string|max:2000',
        ]);

        $this->guildService->updateAnnouncement($guild, $request->announcement);
        
        return redirect()->back()->with('success', 'Cập nhật thông báo thành công!');
    }
}
