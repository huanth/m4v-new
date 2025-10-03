<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Show user profile
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $currentUser = auth()->user();
        
        return view('users.show', compact('user', 'currentUser'));
    }
}