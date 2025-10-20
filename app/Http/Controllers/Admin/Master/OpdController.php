<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Helpers\JsonResponseHelper;
use App\Models\Opd;
use App\Models\ProfilPegawai;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;
use Throwable;

class OpdController extends Controller
{
    private $logContext = 'master_opd';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Opd::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    return '<img src="' . $row->logo_url . '" class="rounded" alt="logo" style="width: 40px;">';
                })
                ->addColumn('alias', function ($row) {
                    return $row->alias;
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-pen"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['logo', 'actions'])
                ->make(true);
        }

        $data['title'] = "OPD";
        $data['subtitle'] = "Manajemen OPD";

        return view('admin.master.opd.index', $data);
    }


    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);

        $fotoPath = "";

        try {
            $validated = $request->validate([
                'nama' => 'required|unique:opd,nama',
                'alias' => 'required|unique:opd,alias',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:ratio=1/1',
            ]);

            DB::beginTransaction();

            if ($request->hasFile('logo')) {
                $validated['logo'] = Helper::storeFile($request->file('logo'), 'opd/logo');
                $fotoPath = $validated['logo'];
            }

            $data = Opd::create($validated);

            Helper::createLog($data, 'created', $this->logContext, 'membuat OPD: ' . $data->nama, [], $data);

            DB::commit();

            return JsonResponseHelper::success($data, JsonResponseHelper::$SUCCESS_STORE, 201);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();

            if ($fotoPath) {
                Storage::delete($fotoPath);
            }

            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    public function storeFromSso(Request $request)
    {
        allowOnlyAjax($request);

        try {
            $validated = $request->validate([
                'opds' => 'required|array',
                'replaceable' => 'boolean',
            ]);

            DB::beginTransaction();

            foreach ($request->opds as $opd) {
                $opd = json_decode($opd, true);

                $data = Opd::updateOrCreate(
                    ['id' => $opd['id']],
                    [
                        'nama' => $opd['nama'],
                        'alias' => $opd['alias'] ?? null,
                        'created_at' => $opd['created_at'] ?? null,
                        'updated_at' => $opd['updated_at'] ?? null,
                    ]
                );

                Helper::createLog($data, 'created', $this->logContext, 'membuat OPD dari SSO: ' . $data->nama, [], $data);
            }

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

        $fotoPath = "";

        try {
            $validated = $request->validate([
                'nama' => 'required',
                'alias' => 'required',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:ratio=1/1',
            ]);

            DB::beginTransaction();

            if ($request->hasFile('logo')) {
                $validated['logo'] = Helper::storeFile($request->file('logo'), 'opd/logo');
                $fotoPath = $validated['logo'];
            }

            $data = Opd::findOrFail($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;

            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah OPD: ' . $newData->nama, $oldData, $newData);

            DB::commit();

            if ($request->hasFile('logo') && $oldData->logo) {
                Storage::delete($oldData->logo);
            }

            return JsonResponseHelper::success($data, JsonResponseHelper::$SUCCESS_STORE, 201);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();

            if ($fotoPath) {
                Storage::delete($fotoPath);
            }

            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        allowOnlyAjax($request);

        try {
            $data = Opd::findOrFail($id);
            
            // Selalu cek relasi sebelum menghapus
            $userCount = ProfilPegawai::where('opd_id', $id)->count();

            if ($userCount > 0) {
                throw new Exception("OPD ini masih memiliki $userCount pegawai yang terdaftar. Hapus pegawai terlebih dahulu.");
            }

            // Mulai hapus
            DB::beginTransaction();

            $oldData = clone $data;
            $data->delete();

            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus OPD: ' . $oldData->nama, $oldData, []);
            
            DB::commit();

            if ($oldData->logo) {
                Storage::delete($oldData->logo);
            }

            return JsonResponseHelper::success(['id' => $oldData->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();

            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }
}
