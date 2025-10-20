<?php

namespace App\Http\Controllers\Admin\Master;

use App\Helpers\Helper;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    private $logContext = 'master_opd';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    $row = '
                        <p class="m-0">' . dateTimeShortMonthIndo($row->created_at) . '</p>
                        <small class="text-muted">' . diffToHuman($row->created_at) . '</small>
                    ';
                    return $row;
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '  <a href="javascript:void(0)" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-pen"></i></a>';

                    $btn .= ' <a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['created_at', 'actions'])
                ->make(true);
        }

        $data['title'] = "Permission";
        $data['subtitle'] = "Manajemen permission";

        return view('admin.master.permission.index', $data);
    }

    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);

        try {
            $data = $request->validate([
                'name' => 'required|string|unique:permissions,name',
                'guard_name' => 'required|string',
            ]);

            DB::beginTransaction();

            $data = Permission::create($data);

            Helper::createLog($data, 'created', $this->logContext, 'membuat Permission: ' . $data->name, [], $data);

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
                'name' => 'required|string',
                'guard_name' => 'required|string',
            ]);

            $findSameData = Permission::where('name', $request->name)->first();

            if (isset($findSameData) && $findSameData->id != $request->id) {
                throw ValidationException::withMessages([
                    'name' => ['Nama sudah digunakan.'],
                ]);
            }

            DB::beginTransaction();

            $data = Permission::find($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;

            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah Permission: ' . $newData->name, $oldData, $newData);

            DB::commit();

            return JsonResponseHelper::success($data, JsonResponseHelper::$SUCCESS_UPDATE, 200);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();

            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_UPDATE . " " . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        allowOnlyAjax($request);

        try {
            $data = Permission::findOrFail($id);
            $oldData = clone $data;

            if ($data->roles()->count() > 0) {
                throw new \Exception("Permission ini masih digunakan oleh " . $data->roles()->count() . " role lain. Keluarkan dari permission ini terlebih dahulu.");
            }

            if ($data->users()->count() > 0) {
                throw new \Exception("Permission ini masih digunakan oleh " . $data->users()->count() . " user lain. Keluarkan dari permission ini terlebih dahulu.");
            }

            DB::beginTransaction();

            $data->roles()->detach();
            $data->users()->detach();
            $data->delete();

            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus Permission: ' . $oldData->name, $oldData, []);

            DB::commit();

            return JsonResponseHelper::success([
                'id' => $data->id
            ], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();

            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_DELETE . " " . $e->getMessage());
        }
    }
}
