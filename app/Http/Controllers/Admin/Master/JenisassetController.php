<?php

namespace App\Http\Controllers\Admin\Master;

use Exception;
use Throwable;
use App\Helpers\Helper;
use App\Models\Kecamatan;
use App\Models\JenisAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class JenisassetController extends Controller
{
    private $logContext = 'master_jenis_asset';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JenisAsset::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('foto', function ($row) {
                    $row = '
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="' . ($row->foto_jenis_asset ? asset('storage/' . $row->foto_jenis_asset) : asset('assets/global/img/kardus.png')) . '"  
                                        class="rounded-pill" 
                                        alt="photo-profile" 
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                </div>
                            </div>
                        ';
                    return $row;
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-pen"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_jenis_asset . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['actions', 'foto'])
                ->make(true);
        }

        $data['title'] = "Jenis Asset";
        $data['subtitle'] = "Manajemen Jenis Asset";

        return view('admin.master.jenis_asset.index', $data);
    }


    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);

        try {
            $validated = $request->validate([
                'jenis_asset' => 'required|unique:m_jenis_asset,jenis_asset',
                'foto_jenis_asset' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);


            if ($request->hasFile('foto_jenis_asset')) {
                $file = $request->file('foto_jenis_asset');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('foto_jenis_asset', $fileName, 'public');
                $validated['foto_jenis_asset'] = $filePath;
            } else {
                $validated['foto_jenis_asset'] = null;
            }

            DB::beginTransaction();
            $data = JenisAsset::create($validated);
            Helper::createLog($data, 'created', $this->logContext, 'membuat jenis asset: ' . $data->jenis_asset, [], $data);
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
                'jenis_asset' => 'required',
                'foto_jenis_asset' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            DB::beginTransaction();
            $data = JenisAsset::findOrFail($id);
            $oldData = clone $data;

            if ($request->hasFile('foto_jenis_asset')) {
                if ($data->foto_jenis_asset && Storage::disk('public')->exists($data->foto_jenis_asset)) {
                    Storage::disk('public')->delete($data->foto_jenis_asset);
                }

                $file = $request->file('foto_jenis_asset');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('foto_jenis_asset', $fileName, 'public');
                $validated['foto_jenis_asset'] = $filePath;
            }

            $data->update($validated);
            $newData = clone $data;
            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah jenis asset: ' . $newData->jenis_asset, $oldData, $newData);
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
            $data = JenisAsset::findOrFail($id);
            DB::beginTransaction();

            $oldData = clone $data;
            $data->delete();
            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus jenis asset: ' . $oldData->jenis_asset, $oldData, []);

            if ($oldData->foto_jenis_asset && Storage::exists($oldData->foto_jenis_asset)) {
                Storage::delete($oldData->foto_jenis_asset);
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
