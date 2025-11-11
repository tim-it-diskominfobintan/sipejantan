<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Models\User;
use App\Models\Asset;
use App\Helpers\Helper;
use App\Models\Laporan;
use App\Models\Pelapor;
use App\Models\Teknisi;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Perbaikan;
use App\Models\DokLaporan;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class LaporanController extends Controller
{
    private $logContext = 'Pelapor';
    private $logContext2 = 'Laporan';

    public function index(Request $request, $asset = null)
    {
        $selectedAsset = $asset ? Asset::findOrFail($asset) : null;

        if ($request->ajax()) {
            // $query = Pelapor::with(['laporan', 'kecamatan', 'kelurahan'])->latest();
            $query = Pelapor::with(['laporan.asset.kecamatan', 'laporan.asset.kelurahan'])
                ->latest();

            if ($selectedAsset) {
                $query->whereHas('laporan', function ($q) use ($selectedAsset) {
                    $q->where('asset_id', $selectedAsset->id_asset);
                });
            }

            // filter kecamatan via asset
            if ($request->kecamatan) {
                $query->whereHas('laporan.asset', function ($q) use ($request) {
                    $q->where('kecamatan_id', $request->kecamatan);
                });
            }

            // filter kelurahan via asset
            if ($request->kelurahan) {
                $query->whereHas('laporan.asset', function ($q) use ($request) {
                    $q->where('kelurahan_id', $request->kelurahan);
                });
            }

            // filter status laporan
            if ($request->status) {
                $query->whereHas('laporan', function ($q) use ($request) {
                    $q->where('status_laporan', $request->status);
                });
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kecamatan', function ($row) {
                    return $row->kecamatan->nama_kecamatan;
                })
                ->addColumn('pelapor', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->nik . '<br><br>' . $row->nama . '
                        </div>
                    ';
                })
                ->addColumn('tgl_lapor', function ($row) {
                    $classname = "badge badge-light";
                    // dd($row->laporan->tanggal_laporan);
                    $tgl = Carbon::parse($row->laporan->tanggal_laporan)->translatedFormat('d F Y');
                    return '
                        <div class="' . $classname . '">
                            ' . $tgl . '
                        </div>
                    ';
                })
                ->addColumn('no_lapor', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . optional($row->laporan)->no_laporan . '
                        </div>
                    ';
                })
                ->addColumn('telp', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->no_hp . '
                        </div>
                    ';
                })
                ->addColumn('pj', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->laporan->asset->penanggung_jawab->nama_penanggung_jawab . '
                        </div>
                    ';
                })
                ->addColumn('nama_asset', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->laporan->asset->nama_asset . '
                        </div>
                    ';
                })
                ->addColumn('status', function ($row) {
                    // cari laporan berdasarkan pelapor_id
                    // $laporan = Laporan::where('pelapor_id', $row->id_pelapor)->first();

                    // // default status
                    // $status = 'pending';

                    // if ($laporan) {
                    //     $perbaikanTerakhir = Perbaikan::where('laporan_id', $laporan->id_laporan)
                    //         ->latest()
                    //         ->first();

                    //     if ($perbaikanTerakhir) {
                    //         $status = $perbaikanTerakhir->status_progress ?? 'pending';
                    //     } else {
                    //         $status = optional($row->laporan)->status_laporan ?? 'pending';
                    //     }
                    // }
                    $status = $row->laporan->status_laporan;
                    // tentukan warna badge
                    switch ($status) {
                        case 'pending':
                            $classname = "badge bg-red text-red-fg";
                            break;
                        case 'ditolak':
                            $classname = "badge bg-black text-white-fg";
                            break;
                        case 'proses':
                            $classname = "badge bg-blue text-blue-fg";
                            break;
                        default:
                            $classname = "badge bg-green text-green-fg";
                    }

                    return '
                        <div class="' . $classname . '">
                            ' . ucfirst($status) . '
                        </div>
                    ';
                })
                ->addColumn('kelurahan', function ($row) {
                    return $row->kelurahan->nama_kelurahan;
                })
                ->addColumn('foto', function ($row) {
                    $row = '
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="' . asset('storage/' . $row->foto_identitas) . '"  class="avatar avatar-1" alt="photo-profile" style="width: 80px;">
                            </div>
                        </div>
                    ';
                    return $row;
                })
                ->addColumn('ft_laporan', function ($row) {
                    $foto = optional($row->laporan)->foto_laporan;

                    $src = $foto ? asset('storage/' . $foto) : asset('assets/global/img/no-image.jpeg'); // fallback kalau null
                    $html = '
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="' . $src . '" class="avatar avatar-1" alt="photo-profile" style="width: 80px;">
                            </div>
                        </div>
                    ';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));
                    $status = optional($row->laporan)->status_laporan ?? 'pending'; // amanin null
                    $hidden = ($status == 'proses' || $status == 'selesai') ? '' : 'hidden';
                    $user = Auth::user();

                    $btn = '<div class="btn-group shadow-none">';
                    if ($status == 'pending') {
                        if ($user->roles->first()->name == 'teknisi') {
                        } else {
                            $btn .= "<a href='" . url("admin/cek_status/$row->id_pelapor") . "' class='btn-perbaikan btn btn-primary-light shadow-none'  data-detail='$detail' data-id='$row->id_pelapor'><i class='bi bi-check'></i></a>";
                        }
                    } else {
                        $btn .= "<a href='" . url("admin/perbaikan/$row->id_pelapor") . "' class='btn-perbaikan btn btn-primary-light shadow-none' 
                        $hidden data-detail='$detail' data-id='$row->id_pelapor'><i class='bi bi-gear'></i></a>";
                        $btn .= '<a href="javascript:void(0)" class="btn-teknisi btn btn-info-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_pelapor . '" hidden><i class="bi bi-person-gear"></i></a>';
                        if ($user->roles->first()->name == 'superadmin') {
                            $btn .= '<a href="' . url('admin/laporan/' . $row->id_pelapor . '/edit') . '" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_pelapor . '"><i class="bi bi-pen"></i></a>';
                            $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_pelapor . '"><i class="bi bi-trash"></i></a>';
                        }
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['kecamatan', 'kelurahan', 'actions', 'foto', 'pelapor', 'tgl_lapor', 'no_lapor', 'ft_laporan', 'telp', 'status', 'pj', 'nama_asset'])
                ->make(true);
        }

        $data['title'] = "Laporan";
        $data['subtitle'] = "Manajemen Laporan";
        $data['kecamatan'] = Kecamatan::all();
        $data['kelurahan'] = Kelurahan::all();
        $data['asset'] = Asset::all();
        $data['teknisi'] = Teknisi::all();
        return view('admin.laporan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($asset = null)
    {
        $user = Auth::user();

        if ($asset) {
            $selectedAsset = Asset::findorFail($asset);
        } else {
            $selectedAsset = null;
        }

        if ($user->roles->first() && $user->roles->first()->name === 'admin') {
            $teknisi = Teknisi::all();
        } else {
            $teknisi = Teknisi::where('id_teknisi', $user->teknisi_id)->get();
        }

        $data['title'] = "Manajemen Laporan";
        $data['subtitle'] = "Tambah Laporan";
        $data['kecamatan'] = Kecamatan::all();
        $data['kelurahan'] = Kelurahan::all();
        $data['asset'] = Asset::all();
        $data['selectedAsset'] = $selectedAsset;
        $data['teknisi'] = $teknisi;
        $data['user'] = Auth::user();

        return view('admin.laporan.form_create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $asset = null)
    {
        allowOnlyAjax($request);
        // cek apakah asset sedang dalam perbaikan atau sudah selesai, jika sudah selesai maka terima laporan, jika tidak maka alert asset sedang dalam perbaikan

        $user = auth()->user();
        $roleName = $user->roles->pluck('name')->first();

        // cek teknisi atau umum
        $isUmum = $request->data_teknisi === 'umum';

        if ($roleName == 'teknisi') {
            // kalau teknisi, data pelapor otomatis → tidak perlu validasi pelapor
            $rules = [
                'no_laporan'        => 'required|string|max:50|unique:laporan,no_laporan',
                'deskripsi_laporan' => 'required|string',
                'tanggal_laporan'   => 'required|date',
                'foto_laporan'      => 'required',
                'foto_laporan.*'      => 'image|mimes:jpg,jpeg,png|max:2048',
            ];
        } else {
            // kalau admin/umum → wajib input data pelapor
            $rules = [
                'nik'           => 'required|string|max:20',
                'nama'          => 'required|string|max:150',
                'email'         => 'required|email|max:150',
                'no_hp'         => 'required|string|max:15',
                'no_laporan'    => 'required|string|max:50|unique:laporan,no_laporan',
                'deskripsi_laporan' => 'required|string',
                'tanggal_laporan'   => 'required|date',
                'foto_laporan'      => 'required',
                'foto_laporan.*'      => 'image|mimes:jpg,jpeg,png|max:2048',
            ];
        }

        if (!$asset) {
            $rules = array_merge($rules, [
                'asset_id' => 'required|integer',
            ]);
            $asset_id = $request->asset_id;
        } else {
            $asset_id = $asset;
        }

        $cek = Laporan::where('asset_id',  $asset_id)->whereIn('status_laporan', ['proses', 'pending'])->first();
        if ($cek) {
            return JsonResponseHelper::error('Maaf, Aset sedang dalam perbaikan atau laporan sedang diproses oleh teknisi.', JsonResponseHelper::$FAILED_STORE, 422);
        }

        // Tambahan rules kalau pelapor umum
        if ($isUmum) {
            $rules['kecamatan_id']   = 'required|integer';
            $rules['kelurahan_id']   = 'required|integer';
            $rules['alamat']         = 'required|string';
            $rules['foto_identitas'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            // status & teknisi_id
            if ($roleName == 'admin') {
                if ($isUmum) {
                    $status_laporan = 'pending';
                    $teknisi = NULL;
                    $kecamatan = $validated['kecamatan_id'];
                    $kelurahan = $validated['kelurahan_id'];
                    $alamat    = $validated['alamat'];
                    $fotoIdentitasPath = $request->file('foto_identitas')->store('identitas', 'public');
                } else {
                    $data_teknisi = Teknisi::findOrFail($request->data_teknisi);
                    $status_laporan = 'pending';
                    $teknisi_id = $data_teknisi->id_teknisi;
                    $kecamatan = $data_teknisi->kecamatan_id;
                    $kelurahan = $data_teknisi->kelurahan_id;
                    $alamat    = $data_teknisi->alamat_teknisi;
                    $fotoIdentitasPath = $data_teknisi->foto_teknisi; // ambil dari DB teknisi
                }
            } elseif ($roleName == 'teknisi') {
                $status_laporan = 'proses';
                $teknisi = Teknisi::findOrFail($user->teknisi_id);
                $teknisi_id = $teknisi->id_teknisi;
                $kecamatan = $teknisi->kecamatan_id;
                $kelurahan = $teknisi->kelurahan_id;
                $alamat    = $teknisi->alamat_teknisi;
                $fotoIdentitasPath = $teknisi->foto_teknisi;
            } else {
                $status_laporan = 'pending';
                $teknisi = NULL;
                $kecamatan = $validated['kecamatan_id'];
                $kelurahan = $validated['kelurahan_id'];
                $alamat    = $validated['alamat'];
                $fotoIdentitasPath = $request->file('foto_identitas')->store('identitas', 'public');
            }

            if ($roleName == 'teknisi') {
                // Ambil data teknisi otomatis
                $teknisi = Teknisi::findOrFail($user->teknisi_id);

                $pelapor = new Pelapor();
                $pelapor->nik            = $teknisi->nik_teknisi;
                $pelapor->nama           = $teknisi->nama_teknisi;
                $pelapor->email          = $teknisi->email_teknisi;
                $pelapor->no_hp          = $teknisi->hp_teknisi;
                $pelapor->kecamatan_id   = $teknisi->kecamatan_id;
                $pelapor->kelurahan_id   = $teknisi->kelurahan_id;
                $pelapor->alamat         = $teknisi->alamat_teknisi;
                $pelapor->foto_identitas = $teknisi->foto_teknisi;
                $pelapor->created_by     = $user->username;
                $pelapor->save();
            } else {
                // Admin / umum isi manual
                $pelapor = new Pelapor();
                $pelapor->nik            = $validated['nik'];
                $pelapor->nama           = $validated['nama'];
                $pelapor->email          = $validated['email'];
                $pelapor->no_hp          = $validated['no_hp'];
                $pelapor->kecamatan_id   = $kecamatan;
                $pelapor->kelurahan_id   = $kelurahan;
                $pelapor->alamat         = $alamat;
                $pelapor->foto_identitas = $fotoIdentitasPath;
                $pelapor->created_by     = $user->username;
                $pelapor->save();
            }

            $logs[] = fn() => Helper::createLog($pelapor, 'created', $this->logContext, 'menambahkan data pelapor : ' . $pelapor->nama);

            // Simpan Laporan
            $laporan = new Laporan();
            $laporan->no_laporan        = $validated['no_laporan'];
            $laporan->pelapor_id        = $pelapor->id_pelapor;
            $laporan->asset_id          = $asset_id;
            $laporan->deskripsi_laporan = $validated['deskripsi_laporan'];
            $laporan->tanggal_laporan   = $validated['tanggal_laporan'];
            $laporan->status_laporan    = $status_laporan;
            $laporan->created_by        = $user->username;
            $laporan->teknisi_id        = $teknisi_id ?? null;
            $laporan->save();

            // if ($request->hasFile('foto_laporan')) {
            //     $path2 = $request->file('foto_laporan')->store('foto_laporan', 'public');
            //     $laporan->foto_laporan = $path2;
            // }

            if ($request->hasFile('foto_laporan')) {
                foreach ($request->file('foto_laporan') as $file) {
                    $path2 = $file->store('foto_laporan', 'public');

                    $dok = new DokLaporan();
                    $dok->laporan_id  = $laporan->id_laporan;
                    $dok->file_laporan = $path2;
                    $dok->created_by   = $user->username;
                    $dok->save();
                }
            }

            $logs[] = fn() => Helper::createLog($laporan, 'created', $this->logContext2, 'menambahkan data laporan dengan no laporan: ' . $laporan->no_laporan);

            Helper::createBatchLogs(...array_filter($logs));
            DB::commit();

            return JsonResponseHelper::success($pelapor, JsonResponseHelper::$SUCCESS_STORE, 201);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = Pelapor::findOrFail($id);
        $data['title'] = "Manajemen Laporan";
        $data['subtitle'] = "Ubah Laporan";
        $data['kecamatan'] = Kecamatan::all();
        $data['kelurahan'] = Kelurahan::all();
        $data['asset'] = Asset::all();
        $data['pelapor'] = $data;
        $data['laporan'] = Laporan::where('pelapor_id', $id)->first();
        return view('admin.laporan.form_update', $data);
    }

    public function update(Request $request)
    {
        allowOnlyAjax($request);
        try {
            if ($request->status_laporan == 'ditolak') {
                $validated = $request->validate([
                    'ket_tolak' => 'required|string',
                    'status_laporan' => 'required',
                ]);
            } else {
                $validated = $request->validate([
                    'teknisi_id' => 'required',
                    'status_laporan' => 'required',
                ]);
            }

            DB::beginTransaction();
            $data = Laporan::findOrFail($request->id_laporan);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;
            Helper::createLog($data, 'updated',  $this->logContext2, 'mengubah data laporan status: ' . $newData->status_laporan . ' dan teknisi : ' . $newData->teknisi_id, $oldData, $newData);
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

    public function update_pelapor(Request $request, $id)
    {
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'nik'           => 'required|string|max:20',
                'nama'          => 'required|string|max:150',
                'email'         => 'required|email|max:150',
                'no_hp'         => 'required|string|max:15',
                'kecamatan_id'  => 'required|integer',
                'kelurahan_id'  => 'required|integer',
                'alamat'        => 'required|string',
                'foto_identitas' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

                // Data Laporan
                'no_laporan'        => 'required|string|max:50',
                'asset_id'          => 'required|integer',
                'deskripsi_laporan' => 'required|string',
                'tanggal_laporan'   => 'required|date',
                'foto_laporan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            DB::beginTransaction();

            $dataPelapor = Pelapor::findOrFail($id);
            $oldDataPelapor = clone $dataPelapor;

            if ($request->hasFile('foto_identitas')) {
                if ($dataPelapor->foto_identitas) {
                    Storage::delete($dataPelapor->foto_identitas);
                }
                $dataPelapor->foto_identitas = $request->file('foto_identitas')->store('identitas');
            }

            $dataPelapor->update([
                'nik'          => $validated['nik'],
                'nama'         => $validated['nama'],
                'email'        => $validated['email'],
                'no_hp'        => $validated['no_hp'],
                'kecamatan_id' => $validated['kecamatan_id'],
                'kelurahan_id' => $validated['kelurahan_id'],
                'alamat'       => $validated['alamat'],
                'created_by'   => Auth::user()->username,
            ]);

            $newDataPelapor = clone $dataPelapor;
            $logs[] = fn() => Helper::createLog($dataPelapor, 'updated', $this->logContext, 'merubah data pelapor : ' . $dataPelapor->nama, $oldDataPelapor, $newDataPelapor);

            $dataLaporan = Laporan::where('pelapor_id', $id)->first();
            $oldDataLaporan = clone $dataLaporan;

            if ($request->hasFile('foto_laporan')) {
                if ($dataLaporan->foto_laporan) {
                    Storage::delete($dataLaporan->foto_laporan);
                }
                $dataLaporan->foto_laporan = $request->file('foto_laporan')->store('foto_laporan');
            }

            $dataLaporan->update([
                'no_laporan'            => $validated['no_laporan'],
                'asset_id'              => $validated['asset_id'],
                'deskripsi_laporan'     => $validated['deskripsi_laporan'],
                'tanggal_laporan'       => $validated['tanggal_laporan'],
            ]);

            $newDataLaporan = clone $dataLaporan;
            $logs[] = fn() => Helper::createLog($dataLaporan, 'updated', $this->logContext2, 'merubah data laporan : ' . $dataLaporan->no_laporan, $oldDataLaporan, $newDataLaporan);
            Helper::createBatchLogs(...array_filter($logs));

            DB::commit();
            return JsonResponseHelper::success($dataPelapor, JsonResponseHelper::$SUCCESS_STORE, 201);
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
        try {
            $dataPelapor = Pelapor::findOrFail($id);
            $dataLaporan = Laporan::where('pelapor_id', $id)->first();
            DB::beginTransaction();
            $oldDataPelapor = clone $dataPelapor;
            $oldDataLaporan = clone $dataLaporan;
            if ($dataPelapor->foto_identitas) {
                Storage::delete($dataPelapor->foto_identitas);
            }

            if ($dataLaporan->foto_laporan) {
                Storage::delete($dataLaporan->foto_laporan);
            }

            $dataPelapor->delete();
            $dataLaporan->delete();
            $logs[] = fn() => Helper::createLog($oldDataPelapor, 'deleted', $this->logContext, 'menghapus data pelapor : ' . $oldDataPelapor->nama, $oldDataPelapor, []);

            $logs[] = fn() => Helper::createLog($oldDataLaporan, 'deleted', $this->logContext2, 'menghapus data laporan : ' . $dataLaporan->no_laporan, $oldDataLaporan, []);

            Helper::createBatchLogs(...array_filter($logs));
            DB::commit();
            return JsonResponseHelper::success(['id' => $oldDataPelapor->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    public function data_teknisi($id)
    {
        return Teknisi::findorFail($id);
    }

    public function export()
    {
        $fileName = 'laporan_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new LaporanExport, $fileName);
    }
}
