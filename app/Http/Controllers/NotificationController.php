<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification as AppNotification;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.ban']);
    }

    /**
     * Show notifications page
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = AppNotification::where('user_id', $user->id)
            ->with('fromUser')
            ->latest()
            ->limit(50)
            ->get();
        $unreadCount = NotificationService::getUnreadCount($user->id);

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        NotificationService::markAsRead($id, $user->id);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        NotificationService::markAllAsRead($user->id);

        return redirect()->back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc!');
    }

    /**
     * Get unread notifications count (AJAX)
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = NotificationService::getUnreadCount($user->id);

        return response()->json(['count' => $count]);
    }

    /**
     * Get latest notifications (AJAX)
     */
    public function getLatest()
    {
        $user = Auth::user();
        $notifications = NotificationService::getNotifications($user->id, 10);

        return response()->json(['notifications' => $notifications]);
    }
}
