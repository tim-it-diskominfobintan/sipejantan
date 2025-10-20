<?php

namespace App\Http\Controllers\Admin\Master;

use Throwable;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\PenanggungJawab;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class PenanggungJawabController extends Controller
{
    private $logContext = 'master_penanggung_jawab';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PenanggungJawab::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-pen"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_penanggung_jawab . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $data['title'] = "Penanggung Jawab";
        $data['subtitle'] = "Manajemen Penanggung Jawab";

        return view('admin.master.penanggung_jawab.index', $data);
    }


    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);

        try {
            $validated = $request->validate([
                'nama_penanggung_jawab' => 'required|unique:m_penanggung_jawab,nama_penanggung_jawab',
                'warna_penanggung_jawab' => 'required',
            ]);

            DB::beginTransaction();
            $data = PenanggungJawab::create($validated);
            Helper::createLog($data, 'created', $this->logContext, 'membuat penanggung jawab: ' . $data->nama_penanggung_jawab, [], $data);
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
                'nama_penanggung_jawab' => 'required',
                'warna_penanggung_jawab' => 'required',
            ]);

            DB::beginTransaction();
            $data = PenanggungJawab::findOrFail($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;
            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah penanggung jawab: ' . $newData->nama_penanggung_jawab, $oldData, $newData);
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
            $data = PenanggungJawab::findOrFail($id);
            DB::beginTransaction();

            $oldData = clone $data;
            $data->delete();
            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus penanggung jawab: ' . $oldData->nama_penanggung_jawab, $oldData, []);

            DB::commit();
            return JsonResponseHelper::success(['id' => $oldData->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }
}
