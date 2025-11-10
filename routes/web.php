<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\ListAssettController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\BarangRusakController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\DetailBarangController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PelaporController;
use App\Http\Controllers\Admin\Master\JalanController;
use App\Http\Controllers\Admin\Master\JenisassetController;
use App\Http\Controllers\Admin\Master\PenanggungJawabController;
use App\Http\Controllers\Admin\Master\OpdController as AdminOpdController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\Master\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\Master\UserController as AdminUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\Master\TeknisiController as MasterTeknisiController;
use App\Http\Controllers\Admin\Master\KecamatanController as AdminKecamatanController;
use App\Http\Controllers\Admin\Master\KelurahanController as AdminKelurahanController;
use App\Http\Controllers\Admin\Master\PermissionController as AdminPermissionController;

Route::get('/', [PengaduanController::class, 'index']);
Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
Route::get('/cari_pengaduan', [PengaduanController::class, 'show'])->name('cari_pengaduan');
Route::get('/pengaduan', [PengaduanController::class, 'show']);
Route::get('/jenisAsset/{id}', [ListAssettController::class, 'show'])->name('jenisAsset.show');
Route::get('/grafik/{id}', [GrafikController::class, 'index']);
Route::get('/informasi_tiket', [TiketController::class, 'index']);
Route::get('jenisAsset/asset/{id}/detail', [ListAssettController::class, 'detail']);
Route::get('/pengaduan/{kecamatan_id}', [PengaduanController::class, 'getKelurahan']);
Route::get('/kelurahan/{kecamatan_id}', [PengaduanController::class, 'getKelurahan']);
Route::get('/tiket', [TiketController::class, 'tiket'])->name('guest.tiket');
Route::get('/pengaduan/success/{id}', [PengaduanController::class, 'success'])->name('pengaduan.success');

// Default Laravel authentication routes without reset, confirm, and verify
Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false,
    'register' => true,
]);

// Custom authentication routes /auth
Route::prefix('auth')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::post('/logout', App\Http\Controllers\Auth\LogoutController::class)->name('logout');
});

// Private file download/view route
Route::get('/file/private/{filename}', function ($filename) {
    // Pastikan user login
    if (!Auth::check()) {
        abort(403, 'Unauthorized');
    }

    $path = storage_path('app/private/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->download($path);
})->middleware('auth');

// Bintan SSO authentication routes
Route::get('/auth/bintan-sso/login', [App\Http\Controllers\Auth\BintanSSOController::class, 'login']);
Route::post('/auth/bintan-sso/logout', [App\Http\Controllers\Auth\BintanSSOController::class, 'logout']);
Route::get('/auth/bintan-sso/callback', [App\Http\Controllers\Auth\BintanSSOController::class, 'callbackFromSSO']);

// Application routes
Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->middleware('role:superadmin|admin|teknisi');
Route::get('admin/dashboard/asset', [AdminDashboardController::class, 'asset'])->middleware('role:superadmin|admin|teknisi');
Route::patch('admin/master/user/lock-status/{userId}', [AdminUserController::class, 'updatelockStatus'])->middleware('role:superadmin|admin');
Route::post('admin/master/opd/sso', [AdminOpdController::class, 'storeFromSso'])->middleware('role:superadmin|admin')->name('opd.sso');
Route::get('admin/master/user/sso', [AdminUserController::class, 'getUserDataFromSso'])->middleware('role:superadmin|admin')->name('opd.sso');
Route::resource('admin/master/opd', AdminOpdController::class)->middleware('role:superadmin|admin');
Route::resource('admin/master/user', AdminUserController::class)->middleware('role:superadmin|admin');
Route::resource('admin/master/role', AdminRoleController::class)->middleware('role:superadmin|admin');
Route::resource('admin/master/permission', AdminPermissionController::class)->middleware('role:superadmin|admin');
Route::resource('admin/master/kecamatan', AdminKecamatanController::class)->middleware('role:superadmin|admin');
Route::resource('admin/master/kelurahan', AdminKelurahanController::class)->middleware('role:superadmin|admin');
Route::resource('admin/master/jenis_asset', JenisassetController::class)->middleware('role:superadmin|admin');
Route::resource('admin/master/penanggung_jawab', PenanggungJawabController::class)->middleware('role:superadmin|admin');
Route::resource('admin/master/teknisi', MasterTeknisiController::class)->middleware('role:superadmin|admin');
//jalan route
Route::get('admin/master/jalan/export', [JalanController::class, 'export'])->name('admin.master.jalan.export')->middleware('role:superadmin|admin|teknisi');
Route::post('admin/master/jalan/import', [JalanController::class, 'import'])->name('jalan.import')->middleware('role:superadmin|admin|teknisi');
Route::resource('admin/master/jalan', JalanController::class)->middleware('role:superadmin|admin');
Route::post('admin/master/jalan/create', [JalanController::class, 'store'])->middleware('role:superadmin|admin');
Route::put('admin/master/jalan/{id}/edit', [JalanController::class, 'update'])->middleware('role:superadmin|admin');
Route::resource('admin/barang', BarangController::class)->middleware('role:superadmin|admin');
Route::get('admin/detail_barang/{id}', [DetailBarangController::class, 'index'])->middleware('role:superadmin|admin');
Route::post('admin/detail_barang/{id}', [DetailBarangController::class, 'store'])->middleware('role:superadmin|admin');
Route::put('admin/detail_barang/{id}', [DetailBarangController::class, 'update'])->middleware('role:superadmin|admin');
Route::delete('admin/detail_barang/{id}', [DetailBarangController::class, 'destroy'])->middleware('role:superadmin|admin');
//asset route
Route::resource('admin/asset', AssetController::class)->middleware('role:superadmin|admin');
Route::post('admin/asset/create', [AssetController::class, 'store'])->middleware('role:superadmin|admin');
Route::put('admin/asset/{id}/edit', [AssetController::class, 'update'])->middleware('role:superadmin|admin');
Route::get('admin/asset/{id}/detail', [AssetController::class, 'show'])->middleware('role:superadmin|admin|teknisi');

Route::resource('admin/stok_opname', StokOpnameController::class)->middleware('role:superadmin|admin');
Route::post('admin/detail_stok_opname', [StokOpnameController::class, 'store'])->middleware('role:superadmin|admin');
Route::get('trans_barang_perbaikan', [StokOpnameController::class, 'trans_barang_perbaikan'])->middleware('role:superadmin|admin');
Route::get('admin/stok_opname/list/{id}', [BarangController::class, 'listOpname'])->middleware('role:superadmin|admin');
Route::get('admin/detail/stok_opname/list/{id}', [DetailBarangController::class, 'listOpname'])->middleware('role:superadmin|admin');
Route::get('admin/barang/list_detail_barang/{id}', [BarangController::class, 'list_detail'])->middleware('role:superadmin|admin');

Route::prefix('admin')->middleware('role:superadmin|admin|teknisi')->group(function () {
    Route::get('laporan/create/{asset?}', [LaporanController::class, 'create'])
        ->name('laporan.create');

    Route::post('laporan/create/{asset?}', [LaporanController::class, 'store'])
        ->name('laporan.store');

    Route::put('laporan/', [LaporanController::class, 'update'])
        ->name('laporan.update');

    Route::get('laporan/{asset?}', [LaporanController::class, 'index'])
        ->where('asset', '[0-9]+')
        ->name('laporan.index');

    Route::resource('laporan', LaporanController::class)
        ->except(['index', 'create', 'store', 'show']);
});

Route::resource('admin/rusak', BarangRusakController::class)->middleware('role:superadmin|admin|teknisi');
Route::get('admin/detail_barang_status/{id}', [BarangRusakController::class, 'detail'])->middleware('role:superadmin|admin|teknisi');

Route::get('admin/perbaikan/laporan/{id}', [PerbaikanController::class, 'detail_laporan'])->middleware('role:superadmin|admin|teknisi');
Route::get('admin/perbaikan/{id}', [PerbaikanController::class, 'index'])->middleware('role:superadmin|admin|teknisi');
Route::get('admin/cek_status/{id}', [PerbaikanController::class, 'cek'])->middleware('role:superadmin|admin|teknisi');
Route::post('admin/cek_status/{id}', [PerbaikanController::class, 'cek_post'])->middleware('role:superadmin|admin|teknisi');
Route::get('admin/perbaikan/create/{id}', [PerbaikanController::class, 'create'])->middleware('role:superadmin|admin|teknisi');
Route::post('admin/perbaikan/create/{id}', [PerbaikanController::class, 'store'])->middleware('role:superadmin|admin|teknisi');
Route::get('admin/perbaikan/detail/{id}', [PerbaikanController::class, 'show'])->middleware('role:superadmin|admin|teknisi');
Route::get('admin/perbaikan/edit/{id}', [PerbaikanController::class, 'edit'])->middleware('role:superadmin|admin|teknisi');
Route::post('admin/perbaikan/edit/{id}', [PerbaikanController::class, 'update'])->middleware('role:superadmin|admin|teknisi');
Route::delete('admin/perbaikan/delete/{id}', [PerbaikanController::class, 'destroy'])->middleware('role:superadmin|admin|teknisi');


Route::get('data_teknisi/{id}', [LaporanController::class, 'data_teknisi'])
    ->name('data_teknisi')
    ->middleware('role:superadmin|admin|teknisi');

Route::get('admin/profile/me/activity', [AdminProfileController::class, 'myActivity'])->middleware('role:superadmin|admin|teknisi');
Route::resource('admin/profile', AdminProfileController::class)->middleware('role:superadmin|admin|teknisi');

// tidak digunakan
Route::resource('admin/perbaikan', PerbaikanController::class)->middleware('role:superadmin|admin');
Route::get('admin/perbaikan/laporan/{id}', [PerbaikanController::class, 'detail_laporan'])->middleware('role:superadmin|admin');
