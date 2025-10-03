<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Ban;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class BanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show ban form
     */
    public function create(User $user)
    {
        $currentUser = Auth::user();
        
        // Check if current user can ban target user
        if (!$currentUser->canBan($user)) {
            abort(403, 'Bạn không có quyền ban user này.');
        }

        // Check if target user is already banned
        if ($user->isBanned()) {
            return redirect()->back()->with('error', 'User này đã bị ban.');
        }

        return view('admin.ban.create', compact('user'));
    }

    /**
     * Store ban
     */
    public function store(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Check if current user can ban target user
        if (!$currentUser->canBan($user)) {
            abort(403, 'Bạn không có quyền ban user này.');
        }

        // Check if target user is already banned
        if ($user->isBanned()) {
            return redirect()->back()->with('error', 'User này đã bị ban.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
            'duration_type' => 'required|in:permanent,temporary',
            'duration_value' => 'required_if:duration_type,temporary|integer|min:1',
            'duration_unit' => 'required_if:duration_type,temporary|in:minutes,hours,days,weeks,months,years',
        ]);

        $duration = null;
        if ($request->duration_type === 'temporary') {
            $duration = $this->createDuration($request->duration_value, $request->duration_unit);
        }

        try {
            $ban = Ban::banUser(
                $user->id,
                $currentUser->id,
                $request->reason,
                null, // No description field anymore
                $duration,
                'normal' // Default to normal ban
            );
            
            // Store the duration string for display
            if ($duration) {
                $ban->update(['duration' => $this->getDurationString($request->duration_value, $request->duration_unit)]);
            }

            return redirect()->route('user.profile', $user)
                           ->with('success', "Đã ban user {$user->username} thành công.");
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Có lỗi xảy ra khi ban user: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Unban user
     */
    public function unban(User $user)
    {
        $currentUser = Auth::user();
        
        // Check if current user can ban target user (same logic for unban)
        if (!$currentUser->canBan($user)) {
            abort(403, 'Bạn không có quyền unban user này.');
        }

        if (!$user->isBanned()) {
            return redirect()->back()->with('error', 'User này không bị ban.');
        }

        try {
            Ban::unbanUser($user->id);

            return redirect()->back()
                           ->with('success', "Đã unban user {$user->username} thành công.");
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Có lỗi xảy ra khi unban user: ' . $e->getMessage());
        }
    }

    /**
     * Show ban history for user (Admin only)
     */
    public function history(User $user)
    {
        $currentUser = Auth::user();
        
        // Check if current user can view ban history
        if (!$currentUser->canBan($user) && $currentUser->id !== $user->id) {
            abort(403, 'Bạn không có quyền xem lịch sử ban của user này.');
        }

        $banHistory = $user->getBanHistory();

        return view('admin.ban.history', compact('user', 'banHistory'));
    }

    /**
     * Show public ban history for user (Anyone can view)
     */
    public function publicHistory(User $user)
    {
        $bans = $user->getBanHistory();
        
        return view('ban.history', compact('user', 'bans'));
    }

    /**
     * Show all banned users
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Only admin and above can view ban list
        if (!$currentUser->isAdmin()) {
            abort(403, 'Bạn không có quyền xem danh sách ban.');
        }

        $bannedUsers = User::whereHas('bans', function ($query) {
            $query->where('is_active', true)
                  ->where(function ($q) {
                      $q->where('is_permanent', true)
                        ->orWhere('expires_at', '>', now());
                  });
        })->with(['bans' => function ($query) {
            $query->where('is_active', true)
                  ->where(function ($q) {
                      $q->where('is_permanent', true)
                        ->orWhere('expires_at', '>', now());
                  });
        }])->get();

        return view('admin.ban.index', compact('bannedUsers'));
    }

    /**
     * Create duration from value and unit
     */
    private function createDuration($value, $unit)
    {
        switch ($unit) {
            case 'minutes':
                return CarbonInterval::minutes($value);
            case 'hours':
                return CarbonInterval::hours($value);
            case 'days':
                return CarbonInterval::days($value);
            case 'weeks':
                return CarbonInterval::weeks($value);
            case 'months':
                return CarbonInterval::months($value);
            case 'years':
                return CarbonInterval::years($value);
            default:
                return CarbonInterval::days($value);
        }
    }

    /**
     * Get duration string for display
     */
    private function getDurationString($value, $unit)
    {
        $unitNames = [
            'minutes' => 'phút',
            'hours' => 'giờ',
            'days' => 'ngày',
            'weeks' => 'tuần',
            'months' => 'tháng',
            'years' => 'năm',
        ];

        return $value . ' ' . ($unitNames[$unit] ?? $unit);
    }
}