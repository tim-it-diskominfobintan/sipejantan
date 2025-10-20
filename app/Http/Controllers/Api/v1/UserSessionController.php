<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\Helper;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSession;
use Illuminate\Support\Facades\Session;

class UserSessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = UserSession::where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) use ($request) {
                return [
                    'id' => $session->session_id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => Helper::detectUserAgent($session->user_agent),
                    'is_current_device' => $session->session_id === $request->session()->getId(),
                    'last_active' => $session->last_activity ? $session->last_activity->diffForHumans() : '',
                    'logged_at' => $session->created_at,
                ];
            });

        return JsonResponseHelper::success($sessions, JsonResponseHelper::$SUCCESS_INDEX, 200);
    }

    public function show(Request $request)
    {
        $sessionId = $request->session()->getId();

        $session = \App\Models\UserSession::where('user_id', Auth::id())
            ->where('session_id', $sessionId)
            ->first();

        if (! $session) {
            return JsonResponseHelper::error('Session not found.', 'Session not found.', 404);
        }

        return JsonResponseHelper::success([
            'id' => $session->session_id,
            'ip_address' => $session->ip_address,
            'user_agent' => Helper::detectUserAgent($session->user_agent),
            'last_active' => $session->last_activity ? $session->last_activity->diffForHumans() : '',
        ], JsonResponseHelper::$SUCCESS_SHOW, 200);
    }


    public function destroy(Request $request, $sessionId)
    {
        $session = UserSession::where('user_id', Auth::id())
            ->where('session_id', $sessionId)
            ->firstOrFail();

        app('session')->getHandler()->destroy($sessionId);
        $session->delete();

        return JsonResponseHelper::success([], 'Session revoked successfully.', 200);
    }

    public function destroyOthers(Request $request)
    {
        $currentSessionId = $request->session()->getId();

        $sessions = UserSession::where('user_id', Auth::id())
            ->where('session_id', '!=', $currentSessionId)
            ->get();

        foreach ($sessions as $session) {
            app('session')->getHandler()->destroy($session->session_id);
            $session->delete();
        }

        return JsonResponseHelper::success([], 'Other sessions revoked successfully.', 200);
    }
}
