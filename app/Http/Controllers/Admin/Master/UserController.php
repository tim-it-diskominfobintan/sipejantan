<?php

namespace App\Http\Controllers\Admin\Master;

use App\Helpers\Helper;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\AssignUsersOpd;
use App\Models\AuthProvider;
use App\Models\Opd;
use App\Models\ProfilAdmin;
use App\Models\ProfilPegawai;
use App\Models\User;
use App\Models\UserAuthProviderBinding;
use App\Services\BintanSsoTokenService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private $logContext = "master_user";
    private $bintanSsoTokenService;

    public function __construct()
    {
        $this->bintanSsoTokenService = new BintanSsoTokenService();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles', 'role', 'opd', 'auth_provider_bindings')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    $opd = $row->assignedAktifOpd->map(function ($opd) {
                        return $opd->opd;
                    });

                    $opdList = '<ul><li>Tidak ada OPD</li></ul>';

                    if (count($opd) > 0) {
                        $opdList = '<ul>';

                        foreach ($opd as $d) {
                            $opdList .= '<li>' . $d->nama . '</li>';
                        }

                        $opdList .= '</ul>';
                    }

                    $row = '
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <img src="' . $row->photo_profile_url . '" class="rounded-pill mt-2" alt="photo-profile" style="width: 40px;">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <small class="fw-bold">' . $row->name . ($row->id === auth()->user()->id ? ' (Saya)' : '') . '</small><br>
                                <small class="text-muted">' . $row->username . '</small><br>
                                <small class="text-muted">' . $row->email . '</small><br><br>

                                <small class="text-muted">' . $opdList . '</small>
                            </div>
                        </div>
                    ';
                    return $row;
                })
                ->addColumn('status', function ($row) {
                    $classname = "badge badge-light-success";
                    $keterangan = $row->status !== 'active' ? $row->status_description : '';

                    if ($row->status === 'locked') {
                        $classname = "badge badge-light-danger";
                    } else if ($row->status === 'inactive') {
                        $classname = "badge badge-light-warning";
                    }

                    $row = '
                         <div class="' . $classname . '">
                            ' . $row->status . '
                        </div>
                        <br>
                        <small class="text-muted mt-1">' . $keterangan . '</small>
                    ';
                    return $row;
                })
                ->addColumn('role', function ($row) {
                    $classname = "badge badge-light";
                    $row = '
                        <div class="' . $classname . '">
                            ' . optional($row->roles)->first()->name . '
                        </div>
                    ';
                    return $row;
                })
                ->addColumn('last_login_at', function ($row) {
                    $userAgent = Helper::detectUserAgent($row->last_login_device);
                    $row = '
                        <p class="m-0">' . ($row->last_login_at ? dateTimeShortMonthIndo($row->last_login_at) : '<div class="badge badge-light">Belum pernah login</div>') . '</p>
                        <small class="text-muted">' . ($row->last_login_at ? diffToHuman($row->last_login_at) : '') . '</small>
                        <br>
                        <small class="text-muted">
                            <span>' . ($row->last_login_ip ? ($row->last_login_ip . ', ' . $userAgent['browser'] . ' - ' . $userAgent['platform']) : '') . '</span>
                        
                        </small>
                    ';
                    return $row;
                })
                ->addColumn('auth_provider', function ($row) {
                    $row = $row->auth_provider_bindings;

                    if (!empty($row)) {
                        $row = implode(", ", ['Self', ...$row->pluck('name')->toArray()]);

                        return $row;
                    }

                    return "Self";
                })
                ->addColumn('joined_at', function ($row) {
                    $row = '
                        <p class="m-0">' . dateTimeShortMonthIndo($row->joined_at) . '</p>
                        <small class="text-muted">' . diffToHuman($row->joined_at) . '</small>
                    ';
                    return $row;
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));
                    $lockIconClassName = $row->status === 'locked' ? 'btn-light' : 'btn-light';
                    $lockIcon = $row->status === 'locked' ? '<i class="bi bi-unlock"></i>' : '<i class="bi bi-lock"></i>';

                    $btn = '<div class="btn-group shadow-none">';

                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-pen"></i></a>';

                    $btn .=  '<a href="javascript:void(0)" class="btn-update-lock-status btn ' . $lockIconClassName . ' shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '">' . $lockIcon . '</a>';

                    if ($row->id !== auth()->user()->id) {
                        $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id . '"><i class="bi bi-trash"></i></a>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['role', 'username', 'status', 'last_login_at', 'joined_at', 'actions'])
                ->make(true);
        }

        $data['title'] = "User";
        $data['subtitle'] = "Manajemen user";
        $data['roles'] = Role::get();
        $data['opds'] = Opd::get();

        return view('admin.master.user.index', $data);
    }

    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);

        $fotoPaths = [];

        $rules = [
            'username' => 'required|unique:users,username',
            'name' => 'required',
            'email' => 'required|email:dns|unique:users,email',
            'auth_provider' => 'required',
            'password' => [
                'required_if:auth_provider,self',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'status' => 'required|in:active,inactive,locked',
            'status_description' => 'required_if:status,locked',
            'role_id' => 'required',
        ];

        // hanya kalau SSO Bintan perlu OPD
        if ($request->filled('opd_ids') && $request->auth_provider === 'bintan-sso') {
            $rules['opd_ids'] = 'required|array';
            $rules['opd_ids.*'] = 'exists:opd,id';
        }

        if ($request->role_id === '2') {
            $rules['opd_ids'] = 'required|array';
            $rules['opd_ids.*'] = 'exists:opd,id';
        }

        if ($request->photo_profile_url == '') {
            $rules['photo_profile'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        try {
            $request->validate($rules);

            // siapkan data user
            $dataUser = $request->only(['name', 'username', 'email', 'status', 'status_description']);
            $dataUser['password'] = $this->_encryptingPassword($request->password);

            if ($request->hasFile('photo_profile')) {
                $dataUser['photo_profile'] = Helper::storeFile($request->file('photo_profile'), 'images/profile');
                $fotoPaths[] = $dataUser['photo_profile'];
            } elseif ($request->filled('photo_profile_url')) {
                $dataUser['photo_profile'] = Helper::storeFileFromUrl($request->photo_profile_url, [], 'images/profile');
                $fotoPaths[] = $dataUser['photo_profile'];
            }

            DB::beginTransaction();

            // tampung semua logging
            $logs = [];

            // buat user
            $user = User::create($dataUser);
            $user->assignRole($request->role_id);
            $logs[] = fn() => Helper::createLog($user, 'created', $this->logContext, 'Menambahkan User: ' . $user->name, [], $user);

            if ($request->auth_provider != 'self') {
                $findExternalAuthProvider = AuthProvider::where('slug', $request->auth_provider)->first();

                UserAuthProviderBinding::create([
                    'user_id' => $user->id,
                    'auth_provider_id' => $findExternalAuthProvider->id,
                    'auth_provider_user_id' => $request->auth_provider_user_id
                ]);
            }

            // assign OPD jika ada
            if (!empty($request->opd_ids)) {
                // nonaktifkan dulu semua existing
                AssignUsersOpd::where('user_id', $user->id)->update(['status' => 'tidak aktif']);

                foreach ($request->opd_ids as $id) {
                    $assignment = AssignUsersOpd::updateOrCreate(
                        ['user_id' => $user->id, 'opd_id' => $id],
                        ['status' => 'aktif']
                    );

                    $assignment->load('opd');
                    $logs[] = fn() => Helper::createLog($assignment, 'created', $this->logContext, 'Menambahkan OPD: ' . ($assignment->opd?->nama ?? '-') . ' ke User: ' . $user->name, [], $assignment);
                }

                // hanya biarkan OPD paling terakhir (terbaru) yang aktif
                AssignUsersOpd::where('user_id', $user->id)->update(['status' => 'tidak aktif']);
                $latest = AssignUsersOpd::where('user_id', $user->id)->orderByDesc('created_at')->first();
                if ($latest) {
                    $latest->update(['status' => 'aktif']);
                }
            }

            // buat profil berdasarkan role
            $roleName = optional(Role::find($request->role_id))->name;
            if (in_array($roleName, ['admin', 'opd', 'superadmin', 'developer'])) {
                $dataAdmin = ['user_id' => $user->id];
                $adminProfile = ProfilAdmin::create($dataAdmin);

                $logs[] = fn() => Helper::createLog($adminProfile, 'created', $this->logContext, 'Menambahkan Admin: ' . $user->name . ' ke User: ' . $user->name, [], $adminProfile);
            } elseif (in_array($roleName, ['pegawai', 'user', 'asn', 'nonasn'])) {
                $dataPegawai = $request->only(['posisi', 'nik', 'nip', 'no_hp', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin']);
                $dataPegawai['user_id'] = $user->id;
                $pegawaiProfile = ProfilPegawai::create($dataPegawai);

                $logs[] = fn() => Helper::createLog($pegawaiProfile, 'created', $this->logContext, 'Menambahkan Pegawai: ' . $user->name . ' ke User: ' . $user->name, [], $pegawaiProfile);
            } else {
                throw new Exception('Logic untuk role ' . $roleName . ' belum diatur!');
            }

            // log role
            $role = Role::find($request->role_id);
            if ($role) {
                $logs[] = fn() => Helper::createLog($role, 'created', $this->logContext, 'Menambahkan Role: ' . $role->name . ' ke User: ' . $user->name, [], $role);
            }

            // eksekusi batch log
            Helper::createBatchLogs(...array_filter($logs));

            DB::commit();

            return JsonResponseHelper::success($user, JsonResponseHelper::$SUCCESS_STORE, 201);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
            foreach ($fotoPaths as $path) {
                Storage::delete($path);
            }
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }


    // public function store(Request $request)
    // {
    //     allowOnlyAjax($request);

    //     $fotoPaths = [];

    //     try {
    //         $rules = [
    //             'username' => 'required|unique:users,username',
    //             'name' => 'required',
    //             'email'    => 'required|email:dns|unique:users,email',
    //             'auth_provider' => 'required',
    //             'password' => [
    //                 'required_if:auth_provider,self',
    //                 Password::min(8)
    //                     ->letters()
    //                     ->mixedCase()
    //                     ->numbers()
    //                     ->symbols()
    //                     ->uncompromised()
    //             ],
    //             'status'             => 'required|in:active,inactive,locked',
    //             'status_description' => 'required_if:status,locked',
    //             'role_id'            => 'required',
    //             'opd_ids'            => 'required_if:auth_provider,bintan-sso,array'
    //         ];

    //         if ($request->photo_profile_url == '') {
    //             $rules['photo_profile'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
    //         }

    //         // validasi
    //         $request->validate($rules);

    //         // assignmen data
    //         $dataUser = $request->only(['name', 'username', 'email', 'status', 'status_description', 'auth_provider', 'auth_provider_user_id']);
    //         $dataUser['password'] = Hash::make($request->password);
    //         $dataPegawai = $request->only(['nik', 'nip', 'no_hp', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin']);
    //         $dataPegawai = $request->only(['posisi', 'nik', 'nip', 'no_hp', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin']);

    //         if ($request->hasFile('photo_profile')) {
    //             $dataUser['photo_profile'] = Helper::storeFile($request->file('photo_profile'), 'images/profile');
    //             $fotoPaths[] = $dataUser['photo_profile'];
    //         }

    //         if ($request->photo_profile_url != '') {
    //             $dataUser['photo_profile'] = Helper::storeFileFromUrl($request->photo_profile_url, null, 'images/profile');
    //             $fotoPaths[] = $dataUser['photo_profile'];
    //         }

    //         DB::beginTransaction();

    //         // khusus admin
    //         if ($request->role_id == '1' || $request->role_id == '2') {
    //             $logs = [];

    //             $dataUser = User::create($dataUser);
    //             $dataUser->roles()->attach($request->role_id);
    //             $logs[] = fn() => Helper::createLog($dataUser, 'created',  $this->logContext, 'Menambahkan User: ' . $dataUser->name, [], $dataUser);

    //             if (!empty($request->opd_ids)) {
    //                 foreach ($request->opd_ids as $id) {
    //                     $dataUser->opd()->attach($id);

    //                     $assignment = AssignUsersOpd::where('user_id', $dataUser->id)->with('opd')->where('opd_id', $id)->latest()->first();

    //                     $logs[] = fn() => Helper::createLog($assignment, 'created',  $this->logContext, 'Menambahkan OPD: ' . $assignment->opd->nama . ' ke User: ' . $dataUser->name, [], $assignment);
    //                 }

    //                 AssignUsersOpd::where('user_id', $dataUser->id)->update(['status' => 'tidak aktif']);
    //                 $latestOpd = AssignUsersOpd::where('user_id', $dataUser->id)->orderBy('created_at', 'desc')->first();

    //                 AssignUsersOpd::find($latestOpd->id)->update(['status' => 'aktif']);
    //             }

    //             $dataAdmin['user_id'] = $dataUser->id;
    //             $dataAdmin = ProfilAdmin::create($dataAdmin);
    //             $logs[] = fn() => Helper::createLog($dataAdmin, 'created',  $this->logContext, 'Menambahkan Admin: ' . $dataUser->name . ' ke User: ' . $dataUser->name, [], $dataAdmin);

    //             $dataRoleNew = Role::find($request->role_id);
    //             $logs[] = fn() => Helper::createLog($dataRoleNew, 'created',  $this->logContext, 'Menambahkan Role: ' . $dataRoleNew->name . ' ke User: ' . $dataUser->name, [], $dataRoleNew);

    //             Helper::createBatchLogs(...$logs);
    //         }

    //         // jika bukan admin
    //         if ($request->role_id == '3' || $request->role_id == '4' || $request->role_id == '5') {
    //             $logs = [];

    //             $dataUser = User::create($dataUser);
    //             $dataUser->roles()->attach($request->role_id);
    //             $logs[] = fn() => Helper::createLog($dataUser, 'created',  $this->logContext, 'Menambahkan User: ' . $dataUser->name, [], $dataUser);

    //              if (!empty($request->opd_ids)) {
    //                 foreach ($request->opd_ids as $id) {
    //                     $dataUser->opd()->attach($id);

    //                     $assignment = AssignUsersOpd::where('user_id', $dataUser->id)->with('opd')->where('opd_id', $id)->latest()->first();

    //                     $logs[] = fn() => Helper::createLog($assignment, 'created',  $this->logContext, 'Menambahkan OPD: ' . $assignment->opd->nama . ' ke User: ' . $dataUser->name, [], $assignment);
    //                 }

    //                 AssignUsersOpd::where('user_id', $dataUser->id)->update(['status' => 'tidak aktif']);
    //                 $latestOpd = AssignUsersOpd::where('user_id', $dataUser->id)->orderBy('created_at', 'desc')->first();

    //                 AssignUsersOpd::find($latestOpd->id)->update(['status' => 'aktif']);
    //             }

    //             $dataPegawai['user_id'] = $dataUser->id;
    //             $dataPegawaiNew = ProfilPegawai::create($dataPegawai);
    //             $logs[] = fn() => Helper::createLog($dataPegawaiNew, 'created',  $this->logContext, 'Menambahkan Pegawai: ' . $dataUser->nama . ' ke User: ' . $dataUser->name, [], $dataPegawaiNew);

    //             $dataRoleNew = Role::find($request->role_id);
    //             $logs[] = fn() => Helper::createLog($dataRoleNew, 'created',  $this->logContext, 'Menambahkan Role: ' . $dataRoleNew->name . ' ke User: ' . $dataUser->username, [], $dataRoleNew);

    //             Helper::createBatchLogs(...$logs);
    //         }

    //         DB::commit();

    //         return JsonResponseHelper::success($dataUser, JsonResponseHelper::$SUCCESS_STORE, 201);
    //     } catch (ValidationException $error) {
    //         return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
    //     } catch (\Exception $e) {
    //         report($e);

    //         DB::rollBack();

    //         foreach ($fotoPaths as $path) {
    //            Storage::delete($path);
    //         }

    //         return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
    //     }
    // }

    private function _storeFromSso(Request $request) {}

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id)
    {
        allowOnlyAjax($request);
    }

    public function updateLockStatus(Request $request, $id)
    {
        allowOnlyAjax($request);

        $user = User::findOrFail($id);

        $request->validate([
            'status'             => 'required|in:active,inactive,locked',
            'status_description' => 'required_if:status,locked',
        ]);

        $data = $request->all();

        $user->update($data);

        return JsonResponseHelper::success([
            ...$data,
            'updated_at' => now()
        ], JsonResponseHelper::$SUCCESS_UPDATE, 200);
    }

    public function getUserDataFromSso(Request $request)
    {
        $keyword = $request->keyword;
        // $this->bintanSsoTokenService->deleteAccessToken();
        $accessToken = $this->bintanSsoTokenService->getAccessToken();

        if (!$accessToken) {
            return response()->json([
                'error' => 'Unauthorized, no access token on this application.'
            ], 401);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json'
            ])->get(config('bintan-sso-client.endpoint') . '/api/user', [
                'keyword' => $keyword,
            ]);

            if ($response->successful()) {
                $jsonResponse = $response->json();
                $data = $jsonResponse['data'];
                $msg = $jsonResponse['message'];

                return JsonResponseHelper::success($data, $msg, 200);
            } else {
                return JsonResponseHelper::error('Gagal mengambil data dari Bintan SSO', 'Gagal mengambil data dari Bintan SSO', $response->status());
            }
        } catch (RequestException $e) {
            $statusCode = optional($e->response)->status() ?? 500;

            Log::error('SSO Bintan SSO RequestException: ' . $e->getMessage());

            return JsonResponseHelper::error(
                'Terjadi kesalahan saat mengambil data dari Bintan SSO',
                'Terjadi kesalahan saat mengambil data dari IDP: ' . $e->getMessage(),
                $statusCode
            );
        } catch (\Exception $e) {
            Log::error('SSO Bintan SSO error: ' . $e->getMessage());

            return JsonResponseHelper::error('Terjadi kesalahan saat mengambil data dari Bintan SSO', 'Terjadi kesalahan saat mengambil data dari IDP: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        allowOnlyAjax($request);

        try {
            $dataUser = User::findOrFail($id);
            $dataPegawaiQuery = ProfilPegawai::where('user_id', $dataUser->id);
            $dataAdminQuery = ProfilAdmin::where('user_id', $dataUser->id);

            DB::beginTransaction();

            // Hapus semua sesi user
            $userSessions = $dataUser->userSessions ?? [];
            foreach ($userSessions as $session) {
                $session->delete();
            }

            // Sentuh properti agar getOriginal() terisi
            $dataUser->id;
            $userOldData = clone $dataUser;

            $pegawaiModel = $dataPegawaiQuery->first();
            if ($pegawaiModel) $pegawaiModel->id;
            $pegawaiOldData = $pegawaiModel ? clone $pegawaiModel : null;

            $adminModel = $dataAdminQuery->first();
            if ($adminModel) $adminModel->id;
            $adminOldData = $adminModel ? clone $adminModel : null;

            // Hapus data
            $dataUser->delete();
            if ($pegawaiModel) $pegawaiModel->delete();
            if ($adminModel) $adminModel->delete();

            // Buat log dalam 1 batch
            Helper::createBatchLogs(
                fn() => Helper::createLog(
                    $userOldData,
                    'deleted',
                    $this->logContext,
                    'Menghapus User: ' . ($userOldData->name ?? '-'),
                    $userOldData,
                    []
                ),

                fn() => $pegawaiOldData
                    ? Helper::createLog(
                        $pegawaiOldData,
                        'deleted',
                        $this->logContext,
                        'Menghapus Pegawai: ' . ($userOldData->name ?? '-'),
                        $pegawaiOldData,
                        []
                    )
                    : null,

                fn() => $adminOldData
                    ? Helper::createLog(
                        $adminOldData,
                        'deleted',
                        $this->logContext,
                        'Menghapus Admin: ' . ($userOldData->name ?? '-'),
                        $adminOldData,
                        []
                    )
                    : null
            );

            DB::commit();

            // Hapus foto profil jika ada
            if ($userOldData->photo_profile && Storage::exists($userOldData->photo_profile)) {
                Storage::delete($userOldData->photo_profile);
            }

            return JsonResponseHelper::success(
                ['id' => $userOldData->id],
                JsonResponseHelper::$SUCCESS_DELETE,
                200
            );
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();

            return JsonResponseHelper::error(
                $e->getMessage(),
                JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage()
            );
        }
    }

    private function _encryptingPassword($password)
    {
        if ($password) {
            return Hash::make($password);
        } else {
            return Hash::make(12345);
        }
    }
}
