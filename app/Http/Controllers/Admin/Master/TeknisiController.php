<?php

namespace App\Http\Controllers\Admin\Master;

use Exception;
use Throwable;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Laporan;
use App\Models\Teknisi;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Models\PenanggungJawab;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class TeknisiController extends Controller
{
    private $logContext = 'master_teknisi';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Teknisi::with('kelurahan', 'kecamatan', 'user')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nik', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->nik_teknisi . '
                        </div>
                    ';
                })
                ->addColumn('nama', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->nama_teknisi . '
                        </div>
                    ';
                })
                ->addColumn('telp', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->hp_teknisi . '
                        </div>
                    ';
                })
                ->addColumn('nama_kelurahan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->kelurahan->nama_kelurahan . '
                        </div>
                    ';
                })
                ->addColumn('nama_kecamatan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->kecamatan->nama_kecamatan . '
                        </div>
                    ';
                })
                ->addColumn('ft_teknisi', function ($row) {
                    $row = '
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="' . asset('storage/' . $row->foto_teknisi) . '"  class="avatar avatar-1" alt="photo-profile" style="width: 80px;">
                            </div>
                        </div>
                    ';
                    return $row;
                })
                ->addColumn('nama_penanggung_jawab', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->penanggung_jawab->nama_penanggung_jawab . '
                        </div>
                    ';
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-pen"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_teknisi . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['nama_penanggung_jawab', 'actions', 'ft_teknisi', 'telp', 'nik', 'nama', 'nama_kelurahan', 'nama_kecamatan'])
                ->make(true);
        }

        $data['title'] = "Teknisi";
        $data['subtitle'] = "Manajemen Teknisi";
        $data['penanggungjawab'] = PenanggungJawab::all();
        $data['kelurahan'] = Kelurahan::all();
        $data['kecamatan'] = Kecamatan::all();

        return view('admin.master.teknisi.index', $data);
    }


    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);
        $fotoTeknisiPath = null;
        $fotoUserPath = null;
        try {
            $validated = $request->validate([
                'nik_teknisi' => 'required|unique:m_teknisi,nik_teknisi',
                'nama_teknisi' => 'required|unique:m_teknisi,nama_teknisi',
                'email_teknisi' => 'required',
                'hp_teknisi' => 'required',
                'kecamatan_id' => 'required',
                'kelurahan_id' => 'required',
                'alamat_teknisi' => 'required',
                'penanggung_jawab_id' => 'required',
                'foto_teknisi' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'username' => 'required',
                'password' => 'required'
            ]);

            $validated = $request->except(['username', 'password']);

            DB::beginTransaction();
            if ($request->hasFile('foto_teknisi')) {
                $fotoTeknisiPath = $request->file('foto_teknisi')->store('foto_teknisi', 'public');

                $filename = basename($fotoTeknisiPath);
                $fotoUserPath = 'foto_user/' . $filename;

                Storage::disk('public')->copy($fotoTeknisiPath, $fotoUserPath);

                $validated['foto_teknisi'] = $fotoTeknisiPath;
            }
            $data = Teknisi::create($validated);

            $dataUser = User::create([
                'username'    => $request->username,
                'name'        => $request->nama_teknisi,
                'email'       => $request->email_teknisi,
                'teknisi_id'  => $data->id_teknisi,
                'password'    => $this->_encryptingPassword($request->password),
                'photo_profile'    => $fotoUserPath,
            ]);

            $dataUser->assignRole('2');
            Helper::createLog($data, 'created', $this->logContext, 'membuat Teknisi: ' . $data->nama_teknisi, [], $data);
            DB::commit();

            return JsonResponseHelper::success($data, JsonResponseHelper::$SUCCESS_STORE, 201);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id)
    {
        allowOnlyAjax($request);
        $teknisi = Teknisi::findOrFail($id);
        $user = $teknisi->user;
        try {
            $validated = $request->validate([
                'nik_teknisi' => 'required',
                'nama_teknisi' => 'required',
                'email_teknisi' => 'required',
                'hp_teknisi' => 'required',
                'kecamatan_id' => 'required',
                'kelurahan_id' => 'required',
                'alamat_teknisi' => 'required',
                'penanggung_jawab_id' => 'required',
                'foto_teknisi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'username'         => 'required',
                'password'         => 'nullable',
            ]);
            $validated = $request->except(['username', 'password']);
            DB::beginTransaction();
            $fotoUserPath = $user->photo_profile;

            if ($request->hasFile('foto_teknisi')) {
                // hapus foto lama
                if ($teknisi->foto_teknisi && Storage::disk('public')->exists($teknisi->foto_teknisi)) {
                    Storage::disk('public')->delete($teknisi->foto_teknisi);
                }
                if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                    Storage::disk('public')->delete($user->photo_profile);
                }

                // simpan foto baru untuk teknisi
                $fotoTeknisiPath = $request->file('foto_teknisi')->store('foto_teknisi', 'public');
                $filename = basename($fotoTeknisiPath);

                // copy untuk user
                $fotoUserPath = 'foto_user/' . $filename;
                Storage::disk('public')->copy($fotoTeknisiPath, $fotoUserPath);

                $validated['foto_teknisi'] = $fotoTeknisiPath;
            }

            $data = Teknisi::findOrFail($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;

            $user->update([
                'username'       => $request->username,
                'name'           => $request->nama_teknisi,
                'email'          => $request->email_teknisi,
                'photo_profile'  => $fotoUserPath,
                'password'       => $request->filled('password')
                    ? $this->_encryptingPassword($request->password)
                    : $user->password,
            ]);

            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah teknisi: ' . $newData->nama_teknisi, $oldData, $newData);
            DB::commit();
            return JsonResponseHelper::success($data, JsonResponseHelper::$SUCCESS_STORE, 201);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        allowOnlyAjax($request);

        try {
            // cek apakah teknisi sudah terdaftar di laporan atau belum
            $cek = Laporan::where('teknisi_id', $id)->exists();
            if ($cek) {
                throw new Exception("Teknisi sudah ada di data laporan. Hapus data laporan terlebih dahulu terlebih dahulu.");
            }
            $data = Teknisi::findOrFail($id);
            $user = $data->user;
            DB::beginTransaction();

            $oldData = clone $data;
            $data->delete();
            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus teknisi: ' . $oldData->nama_teknisi, $oldData, []);

            if ($oldData->foto_teknisi && Storage::exists($oldData->foto_teknisi)) {
                Storage::delete($oldData->foto_teknisi);
                Storage::disk('public')->delete($user->photo_profile);
            }
            DB::commit();
            return JsonResponseHelper::success(['id' => $oldData->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
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
