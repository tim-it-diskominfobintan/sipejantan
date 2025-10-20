<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // redirect setelah login mau kemana
    protected $redirectTo = RouteServiceProvider::ADMIN_DASHBOARD;
    protected $logContext = "auth_login";

    // middleware untuk guest
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // validasi dan autentikasi
    public function login(Request $request)
    {
        // Validate the login form
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $findUser = User::where('username', $request->username)->first();

        if ($findUser && $findUser->auth_provider == 'bintan-sso') {
             throw ValidationException::withMessages([
                'username' => "Akun ini terdaftar menggunakan Bintan SSO, silahkan login melalui tombol 'Login with Bintan SSO' dibawah ini"
            ]);
        }

        if ($findUser && $findUser->auth_provider == 'google') {
             throw ValidationException::withMessages([
                'username' => "Akun ini terdaftar menggunakan Google, silahkan login melalui tombol 'Continue with Google' dibawah ini"
            ]);
        }

        // Mencoba autentikasi
        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            Log::info("Percobaan masuk gagal menggunakan username '$request->username'");
            Helper::createLoginLog(null, $request, 'failed');

            // Authentication failed
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);

            return;
        }

        // Authentication passed
        $user = Auth::user();  // Mendapatkan instance User yang sudah terautentikasi

        // Cek status akun pengguna
        if ($user->status === 'inactive') {
            Auth::logout();  // Logout pengguna jika status inactive
            throw ValidationException::withMessages([
                'username' => trans('auth.status.inactive'),
            ]);
        }

        if ($user->status === 'locked') {
            Auth::logout();  // Logout pengguna jika status terkunci
            throw ValidationException::withMessages([
                'username' => trans('auth.status.locked'),
            ]);
        }

        // Pastikan $user adalah instance model User sebelum memanggil method update
        if ($user) {
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'last_login_device' => $request->userAgent(),
                'last_device' => $request->userAgent(),
            ]);
        }

        $user = User::find(Auth::id());

        Helper::createLog($user, 'logged_in', $this->logContext, 'User: '.$user->name.' telah login ke dalam sistem melalui Native Authentication', [], $user);
        Helper::createLoginLog($user, $request, 'success', 'self');

        // Redirect setelah login
        return redirect()->intended($this->redirectTo);
    }
}
