<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->isBanned()) {
                $ban = $user->getActiveBan();
                
                // Store ban info in session for display
                session([
                    'user_banned' => true,
                ]);
                
                // Redirect to login if super banned
                if ($ban->ban_type === 'super') {
                    auth()->logout();
                    return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị ban. Vui lòng xem lịch sử ban để biết thêm chi tiết.');
                }
            } else {
                // Clear ban session if user is not banned
                session()->forget(['user_banned']);
            }
        }

        return $next($request);
    }
}