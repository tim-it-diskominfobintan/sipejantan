<?php

namespace App\Http\Controllers\Admin\Master;

use Exception;
use Throwable;
use App\Models\Opd;
use App\Helpers\Helper;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use App\Models\ProfilPegawai;
use App\Exports\KecamatanExport;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class KecamatanController extends Controller
{
    private $logContext = 'master_kecamatan';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kecamatan::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_kecamatan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->kode_kecamatan . '
                        </div>
                    ';
                })
                ->addColumn('nama_kecamatan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->nama_kecamatan . '
                        </div>
                    ';
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-pen"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_kecamatan . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['nama_kecamatan', 'actions', 'kode_kecamatan'])
                ->make(true);
        }

        $data['title'] = "Kecamatan";
        $data['subtitle'] = "Manajemen Kecamatan";

        return view('admin.master.kecamatan.index', $data);
    }


    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);

        try {
            $validated = $request->validate([
                'kode_kecamatan' => 'required|unique:m_kecamatan,kode_kecamatan',
                'nama_kecamatan' => 'required|unique:m_kecamatan,nama_kecamatan',
            ]);

            DB::beginTransaction();
            $data = Kecamatan::create($validated);
            Helper::createLog($data, 'created', $this->logContext, 'membuat Kecematan: ' . $data->nama_kecamatan, [], $data);
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
                'kode_kecamatan' => 'required',
                'nama_kecamatan' => 'required',
            ]);

            DB::beginTransaction();
            $data = Kecamatan::findOrFail($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;
            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah Kecamatan: ' . $newData->nama_kecamatan, $oldData, $newData);
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
            $data = Kecamatan::findOrFail($id);
            DB::beginTransaction();

            $oldData = clone $data;
            $data->delete();
            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus kecamatan: ' . $oldData->nama_kecamatan, $oldData, []);

            DB::commit();
            return JsonResponseHelper::success(['id' => $oldData->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    public function export()
    {
        $fileName = 'kecamatan_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new KecamatanExport, $fileName);
    }
}
