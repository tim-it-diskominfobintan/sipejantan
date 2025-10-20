<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Models\Asset;
use App\Models\Jalan;
use App\Helpers\Helper;
use App\Models\Laporan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisAsset;
use App\Models\DokumenAsset;
use Illuminate\Http\Request;
use App\Models\PenanggungJawab;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class AssetController extends Controller
{
    private $logContext = 'Asset';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Asset::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis_asset', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->jenis_asset->jenis_asset . '
                        </div>
                    ';
                })
                ->addColumn('nama_penanggung_jawab', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->penanggung_jawab->nama_penanggung_jawab . '
                        </div>
                    ';
                })
                ->addColumn('nama_jalan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->jalan->nama_jalan . '
                        </div>
                    ';
                })
                ->addColumn('nama_asset', function ($row) {
                    $row = '
                    <div class="badge badge-light">
                        <span class="kode-text me-2">' . $row->nama_asset . '</span>
                    </div>';
                    return $row;
                })
                ->addColumn('kondisi', function ($row) {
                    if ($row->kondisi == 'baik') {
                        $classname = "badge badge-success";
                        $text = "Baik";
                    } elseif ($row->kondisi == 'rusak_ringan') {
                        $classname = "badge badge-warning";
                        $text = "Rusak Ringan";
                    } elseif ($row->kondisi == 'rusak_berat') {
                        $classname = "badge badge-danger";
                        $text = "Rusak Berat";
                    }
                    return '
                        <div class="' . $classname . '">
                            ' . $text . '
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
                ->addColumn('nama_kelurahan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->kelurahan->nama_kelurahan . '
                        </div>
                    ';
                })
                ->addColumn('foto', function ($row) {
                    $row = '
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="' . ($row->foto_asset ? asset('storage/' . $row->foto_asset) : asset('assets/global/img/kardus.png')) . '"  
                                        class="rounded-pill" 
                                        alt="photo-profile" 
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                </div>
                            </div>
                        ';
                    return $row;
                })
                ->addColumn('kode', function ($row) {
                    $row = '
                    <div class="kode-barcode-container badge badge-light">
                        <span class="kode-text me-2">' . $row->nama_asset . '<br><br>
                        <small>' . $row->kode_asset . '</small></span>
                        <a href="javascript:void(0)" class="btn-barcode btn btn-primary-light shadow-none" data-bs-toggle="modal" data-bs-target="#barcodeModal" data-kode=' . $row->kode_asset . '>
                            <i class="bi bi-upc-scan"></i>
                        </a>
                    </div>';
                    return $row;
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));
                    $existdilaporan = Laporan::where('asset_id', $row->id_asset)->exists();
                    $btn = '<div class="btn-group shadow-none">';
                    $btn .= '<a href="' . url('admin/asset/' . $row->id_asset . '/detail') . '" class="btn-detail btn btn-info-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_asset . '"><i class="bi bi-eye"></i></a>';

                    $btn .= '<a href="' . url('admin/asset/' . $row->id_asset . '/edit') . '" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_asset . '"><i class="bi bi-pen"></i></a>';

                    if (!$existdilaporan) {
                        $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_asset . '" ><i class="bi bi-trash"></i></a>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['jenis_asset', 'nama_penanggung_jawab', 'nama_jalan', 'actions', 'kode', 'foto', 'kode_asset', 'kondisi', 'nama_kecamatan', 'nama_kelurahan'])
                ->make(true);
        }

        $data['title'] = "Asset";
        $data['subtitle'] = "Manajemen Asset";
        $data['jalan'] = Jalan::all();
        $data['penanggung_jawab'] = PenanggungJawab::all();
        $data['jenis_asset'] = JenisAsset::all();

        return view('admin.asset.index', $data);
    }

    public function create()
    {
        $data['title'] = "Manajemen Asset";
        $data['subtitle'] = "Tambah Asset";
        $data['jalan'] = Jalan::all();
        $data['penanggung_jawab'] = PenanggungJawab::all();
        $data['jenis_asset'] = JenisAsset::all();
        $data['kelurahan'] = Kelurahan::all();
        $data['kecamatan'] = Kecamatan::all();

        return view('admin.asset.form_create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'kode_asset' => 'required|unique:asset,kode_asset',
                'nama_asset' => 'required',
                'jenis_asset_id' => 'required',
                'jalan_id' => 'required',
                'penanggung_jawab_id' => 'required',
                'koordinat' => 'required',
                'kondisi' => 'required',
                'kelurahan_id' => 'required',
                'kecamatan_id' => 'required',
                'foto_asset'          => 'nullable|array',
                'foto_asset.*'        => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $validated = $request->except(['foto_asset']);

            DB::beginTransaction();
            $data = Asset::create($validated);

            // simpan foto-foto ke dok_asset
            if ($request->hasFile('foto_asset')) {
                foreach ($request->file('foto_asset') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('foto_asset', $fileName, 'public');

                    DokumenAsset::create([
                        'asset_id'   => $data->id_asset, // sesuaikan PK tabel asset
                        'file_asset' => $filePath,
                        'created_by' => auth()->user()->name ?? 'system'
                    ]);
                }
            }

            Helper::createLog($data, 'created', $this->logContext, 'membuat Data Asset: ' . $data->nama_asset, [], $data);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset, $id)
    {
        $data['title'] = "Manajemen Asset";
        $data['subtitle'] = "Detail Asset";
        $data['jalan'] = Jalan::all();
        $data['penanggung_jawab'] = PenanggungJawab::all();
        $data['jenis_asset'] = JenisAsset::all();
        $data['asset'] = Asset::findOrFail($id);
        $data['kelurahan'] = Kelurahan::all();
        $data['kecamatan'] = Kecamatan::all();
        $data['dokumen_asset'] = DokumenAsset::where('asset_id', $id)->get();
        return view('admin.asset.form_detail', $data);
    }

    public function edit($id)
    {
        $data['title'] = "Manajemen Asset";
        $data['subtitle'] = "Ubah Asset";
        $data['jalan'] = Jalan::all();
        $data['penanggung_jawab'] = PenanggungJawab::all();
        $data['jenis_asset'] = JenisAsset::all();
        $data['asset'] = Asset::findOrFail($id);
        $data['kelurahan'] = Kelurahan::all();
        $data['kecamatan'] = Kecamatan::all();
        return view('admin.asset.form_update', $data);
    }


    public function update(Request $request, $id)
    {
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'kode_asset' => 'required',
                'nama_asset' => 'required',
                'jenis_asset_id' => 'required',
                'jalan_id' => 'required',
                'penanggung_jawab_id' => 'required',
                'koordinat' => 'required',
                'kondisi' => 'required',
                'kelurahan_id' => 'required',
                'kecamatan_id' => 'required',
                'foto_asset'          => 'nullable|array',
                'foto_asset.*'        => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $validated = $request->except(['foto_asset']);
            DB::beginTransaction();
            $data = Asset::findOrFail($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;

            if ($request->hasFile('foto_asset')) {
                // hapus foto lama di DB + storage
                foreach ($data->dokumen as $dok) {
                    Storage::disk('public')->delete($dok->file_asset);
                    $dok->delete();
                }

                // simpan foto baru
                foreach ($request->file('foto_asset') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('foto_asset', $fileName, 'public');

                    DokumenAsset::create([
                        'asset_id'   => $id,
                        'file_asset' => $filePath,
                        'created_by' => auth()->user()->name ?? 'system'
                    ]);
                }
            }

            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah Data Asset: ' . $newData->nama_asset, $oldData, $newData);
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
        try {
            $data = Asset::findOrFail($id);
            DB::beginTransaction();

            $oldData = clone $data;
            $data->delete();

            $dokumen = DokumenAsset::where('asset_id', $id)->get();

            foreach ($dokumen as $dok) {
                Storage::disk('public')->delete($dok->file_asset);
                $dok->delete();
            }

            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus data asset: ' . $oldData->nama_asset, $oldData, []);
            if ($oldData->foto_asset && Storage::exists($oldData->foto_asset)) {
                Storage::delete($oldData->foto_asset);
            }
            DB::commit();
            return JsonResponseHelper::success(['id' => $oldData->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }
}
