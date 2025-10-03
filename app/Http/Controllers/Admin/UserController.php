<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all users
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Only admin and above can view user list
        if (!$currentUser->isAdmin()) {
            abort(403, 'Bạn không có quyền xem danh sách user.');
        }

        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

}