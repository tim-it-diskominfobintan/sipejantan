<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\UserSession;

class TrackUserSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $sessionId = Session::getId();
            $user = Auth::user();
            $getRealtimeUser = User::find($user->id);
            
            if ($getRealtimeUser) {
                UserSession::updateOrCreate(
                    ['session_id' => $sessionId],
                    [
                        'user_id' => $user->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'last_activity' => now(),
                    ]
                );   
            } else {
                // jika user dihapus tiba tiba
                Session::forget('opd_id');
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();
                redirect('/');
            }
        }

        return $next($request);
    }
}