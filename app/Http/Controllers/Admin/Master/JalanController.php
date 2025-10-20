<?php

namespace App\Http\Controllers\Admin\Master;

use Throwable;
use App\Models\Jalan;
use App\Helpers\Helper;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Models\PenanggungJawab;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class JalanController extends Controller
{

    private $logContext = 'master_jalan';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Jalan::with('kecamatan')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_jalan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->nama_jalan . '
                        </div>
                    ';
                })
                ->addColumn('panjang_jalan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->panjang_jalan . '
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
                ->addColumn('nama_penanggung_jawab', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->penanggung_jawab->nama_penanggung_jawab . '
                        </div>
                    ';
                })
                ->addColumn('lihat_map', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '<a href="javascript:void(0)" class="btn-detail btn btn-primary-light shadow-none" data-detail="' . $detail . '"><i class="bi bi-geo-alt"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .= '<a href="' . url('admin/master/jalan/' . $row->id_jalan . '/edit') . '" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-pen"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_jalan . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['nama_kelurahan', 'nama_penanggung_jawab', 'lihat_map', 'actions', 'nama_kecamatan', 'nama_jalan', 'panjang_jalan'])
                ->make(true);
        }

        $data['title'] = "Jalan";
        $data['subtitle'] = "Manajemen Jalan";
        $data['kelurahan'] = Kelurahan::all();
        $data['kecamatan'] = Kecamatan::all();
        $data['penanggungjawab'] = PenanggungJawab::all();

        return view('admin.master.jalan.index', $data);
    }


    public function create()
    {
        $data['title'] = "Manajemen Jalan";
        $data['subtitle'] = "Tambah Jalan";
        $data['kelurahan'] = Kelurahan::all();
        $data['kecamatan'] = Kecamatan::all();
        $data['penanggungjawab'] = PenanggungJawab::all();

        return view('admin.master.jalan.form_create', $data);
    }

    public function store(Request $request)
    {
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'nama_jalan' => 'required',
                'panjang_jalan' => 'required',
                'kecamatan_id' => 'required',
                'kelurahan_id' => 'required',
                'penanggung_jawab_id' => 'required',
                'geojson_jalan' => 'required',
            ]);

            DB::beginTransaction();
            $data = Jalan::create($validated);
            Helper::createLog($data, 'created', $this->logContext, 'membuat Data Jalan: ' . $data->nama_jalan, [], $data);
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

    public function edit($id)
    {
        $data['title'] = "Manajemen Jalan";
        $data['subtitle'] = "Ubah Jalan";
        $data['kelurahan'] = Kelurahan::all();
        $data['penanggungjawab'] = PenanggungJawab::all();
        $data['kecamatan'] = Kecamatan::all();
        $data['jalan'] = Jalan::findOrFail($id);

        return view('admin.master.jalan.form_update', $data);
    }

    public function update(Request $request, $id)
    {
        allowOnlyAjax($request);

        try {
            $validated = $request->validate([
                'nama_jalan' => 'required',
                'panjang_jalan' => 'required',
                'kecamatan_id' => 'required',
                'kelurahan_id' => 'required',
                'penanggung_jawab_id' => 'required',
                'geojson_jalan' => 'required',
            ]);

            DB::beginTransaction();
            $data = Jalan::findOrFail($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;
            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah Data Jalan: ' . $newData->nama_kecamatan, $oldData, $newData);
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
            $data = Jalan::findOrFail($id);
            DB::beginTransaction();

            $oldData = clone $data;
            $data->delete();
            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus data jalan: ' . $oldData->nama_jalan, $oldData, []);

            DB::commit();
            return JsonResponseHelper::success(['id' => $oldData->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }
}
