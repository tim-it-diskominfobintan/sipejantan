<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Helpers\JsonResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AuthProvider;
use App\Models\UserAuthProviderBinding;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use TimItDiskominfoBintan\BintanSSOClient\Auth\BintanSSOAuthClient;

/**
 * Class BintanSSOController
 * 
 * Controller untuk menangani proses autentikasi client yang menggunakan Bintan SSO.
 * 
 * @package TimItDiskominfoBintan\BintanSSOClient
 */
class BintanSSOController extends Controller
{
    private $bintanSSO;
    private $logContext = "auth_bintan-sso_login";

    public function __construct()
    {
        // Instansiasi BintanSSO
        $this->bintanSSO = new BintanSSOAuthClient();
    }

    public function login()
    {
        // Login ke halaman Bintan SSO
        return $this->bintanSSO->signIn();
    }

    public function logout()
    {
        // [START CONTOH] - ini hanya contoh saja dengan kasus: logout dari aplikasi INI
        // ini jika menggunakan session biasa, kalo token silahkan disesuaikan
        Auth::logout();
        // [END CONTOH]

        // Logout ke dari Bintan SSO
        return $this->bintanSSO->signOut();
    }

    // Jika login berhasil, maka akan masuk ke fungsi ini (callbackFromSSO)
    public function callbackFromSSO(Request $request)
    {
        // Dapatkan data user
        $result = $this->bintanSSO->getUserInfo($request);
        $data = $result['data'];

        // mulai dari sini kita bisa set session, simpan data ke db, pokoknya terserah mau ngapain...

        // [START CONTOH] - ini hanya contoh saja dengan kasus: User boleh masuk jika akun sudah terdaftar di aplikasi ini
        $user = User::where('username', $data['username'])->first();
        if (!$user) {
            if ($request->expectsJson()) {
                JsonResponseHelper::error("Akun belum terdaftar.", "Akun belum terdaftar. Hubungi admin aplikasi ini untuk mendaftarkan akun Bintan SSO '" . $data['username'] . "'");
            }

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => "Akun belum terdaftar. Hubungi admin aplikasi ini untuk mendaftarkan akun Bintan SSO '" . $data['username'] . "'",
                ]);
        }

        $findBintanSsoId = AuthProvider::where('slug', 'bintan-sso')->first();
        $findBinding = UserAuthProviderBinding::where('auth_provider_id', $findBintanSsoId->id)
            ->where('auth_provider_user_id', $data['id'])
            ->first();

        if (!$findBinding) {
            if ($request->expectsJson()) {
                JsonResponseHelper::error("Akun belum terdaftar/terikat dengan Bintan SSO.", "Akun belum terdaftar/terikat dengan Bintan SSO.");
            }

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => "Akun belum terdaftar/terikat dengan Bintan SSO.",
                ]);
        }

        // Login user secara manual ke aplikasi ini
        Auth::loginUsingId($user->id);

        if (auth()->check()) {
            // Jika user sudah login

            Helper::createLog($user, 'logged_in', $this->logContext, 'User: ' . $user->name . ' telah login ke dalam sistem melalui Bintan SSO', [], $user);
            Helper::createLoginLog($user, $request, 'success', 'bintan-sso');

            return $this->_redirectIfAuthenticated();
        }

        return $result;
        // [END CONTOH]
    }

    private function _redirectIfAuthenticated()
    {
        $role = auth()->user()->roles->first()->name ?? '';

        switch ($role) {
            case 'admin':
                return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
                break;
            case 'opd':
                return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
                break;
            case 'user':
                return redirect('/');
                break;
            default:
                return redirect('/');
                break;
        }
    }
}
