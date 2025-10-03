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
        return view('profile', compact('user'));
    }

    /**
     * Show public user profile
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        $banHistory = $user->getBanHistory();
        $activeBan = $user->getActiveBan();
        
        return view('user.profile', compact('user', 'currentUser', 'banHistory', 'activeBan'));
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