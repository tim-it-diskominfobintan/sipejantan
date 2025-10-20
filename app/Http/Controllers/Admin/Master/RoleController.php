<?php

namespace App\Http\Controllers\Admin\Master;

use App\Helpers\Helper;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    private $logContext = 'master_role';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::with('permissions')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('permissions', function ($row) {

                    if ($row->permissions->count() > 0) {
                        $permissions = '<div class="badge badge-light-primary mb-1">' . $row->permissions->count() . ' permission: </div><br>';
                        foreach ($row->permissions as $permission) {
                            $permissions .= '<div class="badge badge-light mb-1">' . $permission->name . '</div> ';
                        }
                    } else {
                        $permissions = '-';
                    }

                    return $permissions;
                })
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

                    if ($row->name !== auth()->user()->roles[0]->name) {
                        $btn .= ' <a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id . '"><i class="bi bi-trash"></i></a>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['permissions', 'created_at', 'actions'])
                ->make(true);
        }

        $data['title'] = "Role";
        $data['subtitle'] = "Manajemen role";
        $data['permissions'] = Permission::get();

        return view('admin.master.role.index', $data);
    }

    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);

        try {
            $data = $request->validate([
                'name' => 'required|string|unique:roles,name',
                'guard_name' => 'required|string',
                'description' => 'nullable',
            ]);

            DB::beginTransaction();

            $roleCreated = Role::create($data);
            $roleCreated = Role::find($roleCreated->id);

            $logPermission = null;

            if ($request->filled('permission_ids') && is_array($request->permission_ids)) {
                $roleCreated->permissions()->attach($request->permission_ids);

                $logPermission = Helper::createLog($roleCreated, 'created', $this->logContext, 'membuat Role: ' . $roleCreated->name, [], $data);
            }

             Helper::createBatchLogs(

             );

            Helper::createLog($roleCreated, 'created', $this->logContext, 'membuat Role: ' . $roleCreated->name, [], $data);

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
                'description' => 'nullable'
            ]);

            $findSameData = Role::where('name', $request->name)->first();

            if (isset($findSameData) && $findSameData->id != $id) {
                throw ValidationException::withMessages([
                    'name' => ['Nama sudah digunakan.'],
                ]);
            }

            DB::beginTransaction();

            $data = Role::find($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;

            if ($request->filled('permission_ids') && is_array($request->permission_ids)) {
                $data->permissions()->sync($request->permission_ids);
            } else {
                $data->permissions()->detach();
            }

            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah Permission: ' . $oldData->name, $oldData, $newData);

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
            $data = Role::findOrFail($id);

            if ($data->permissions()->count() > 0) {
                throw new \Exception("Role ini masih digunakan oleh " . $data->permissions()->count() . " permission lain. Keluarkan dari role ini terlebih dahulu.");
            }


            if ($data->users()->count() > 0) {
                throw new \Exception("Role ini masih digunakan oleh " . $data->users()->count() . " user lain. Keluarkan dari role ini terlebih dahulu.");
            }

            DB::beginTransaction();

            $oldData = clone $data;

            $data->permissions()->detach();
            $data->users()->detach();
            $data->delete();

            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus Role: ' . $oldData->name, $oldData, []);

            DB::commit();

            return JsonResponseHelper::success([
                'id' => $data->id
            ], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();

            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }
}
