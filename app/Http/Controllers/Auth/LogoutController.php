<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    protected $logContext = "auth_logout";

    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $user = User::find(Auth::id());

        Helper::createLog($user, 'logged_out', $this->logContext, 'User: ' . $user->name . ' telah logout dari sistem melalui Native Authentication', [], $user);
        Helper::createLogoutLog();

        Session::forget('opd_id');
        Auth::logout();

        return redirect('/');
    }
}
