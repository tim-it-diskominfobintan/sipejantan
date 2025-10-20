<?php

namespace App\Http\Controllers\Admin\Master;

use Exception;
use Throwable;
use App\Helpers\Helper;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class KelurahanController extends Controller
{
    private $logContext = 'master_kelurahan';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kelurahan::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_kelurahan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->kode_kelurahan . '
                        </div>
                    ';
                })
                ->addColumn('nama_kelurahan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->nama_kelurahan . '
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
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-pen"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_kelurahan . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['nama_kecamatan', 'nama_kelurahan', 'actions', 'kode_kelurahan'])
                ->make(true);
        }

        $data['title'] = "Kelurahan";
        $data['subtitle'] = "Manajemen Kelurahan";
        $data['kecamatan'] = Kecamatan::all();

        return view('admin.master.kelurahan.index', $data);
    }


    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);

        try {
            $validated = $request->validate([
                'kode_kelurahan' => 'required|unique:m_kelurahan,kode_kelurahan',
                'nama_kelurahan' => 'required|unique:m_kelurahan,nama_kelurahan',
                'kecamatan_id' => 'required',
            ]);

            DB::beginTransaction();
            $data = Kelurahan::create($validated);
            Helper::createLog($data, 'created', $this->logContext, 'membuat data kelurahan: ' . $data->nama_kecamatan, [], $data);
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

        try {
            $validated = $request->validate([
                'kode_kelurahan' => 'required',
                'nama_kelurahan' => 'required',
                'kecamatan_id' => 'required',
            ]);

            DB::beginTransaction();
            $data = Kelurahan::findOrFail($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;
            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah data kelurahan: ' . $newData->nama_kecamatan, $oldData, $newData);
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
            $data = Kelurahan::findOrFail($id);
            DB::beginTransaction();

            $oldData = clone $data;
            $data->delete();
            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus data kelurahan: ' . $oldData->nama_kecamatan, $oldData, []);

            DB::commit();
            return JsonResponseHelper::success(['id' => $oldData->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }
}
