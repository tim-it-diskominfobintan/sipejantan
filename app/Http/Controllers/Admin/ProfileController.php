<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use TimItDiskominfoBintan\DevPanel\Models\ActivityLogBatch;

class ProfileController extends Controller
{
    private $logContext = 'admin_profile';
    public function myActivity(Request $request)
    {
        if ($request->ajax()) {
            $logQuery = Activity::query()
                ->whereNotIn('event', ['viewed'])
                ->where('causer_id', auth()->user()->id)
                ->whereNotNull('batch_uuid');

            $filteredBatchUuids = $logQuery->pluck('batch_uuid')->unique();

            $data = ActivityLogBatch::with(['activities' => function ($q) {
                $q->orderBy('created_at');
            }])
                ->whereIn('batch_uuid', $filteredBatchUuids)
                ->orderByDesc('latest_log_at')
                ->get()
                ->map(function ($batch) {
                    $log = $batch->activities->first();

                    if (!$log) return null;

                    return (object) [
                        'id' => $log->id,
                        'batch_uuid' => $batch->batch_uuid,
                        'log_count' => $batch->log_count,
                        'log_name' => $log->log_name,
                        'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                        'description' => $log->description,
                        'subject_type' => $log->subject_type,
                        'subject_id' => $log->subject_id,
                        'causer_type' => $log->causer_type,
                        'causer_id' => $log->causer_id,
                        'event' => $log->event,
                        'properties' => $log->properties,
                        'causer' => $log->causer,
                        'activities' => $batch->activities
                    ];
                });

            $data = $data->filter();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        abort(404);
    }

    public function show(Request $request, $id)
    {
        $user = ($id == 'me' ? auth()->user() : User::findOrFail($id));

        if ($request->ajax()) {
            return JsonResponseHelper::success($user, JsonResponseHelper::$SUCCESS_SHOW, 200);
        }

        $data['title'] = "Akun " . ($id == 'me' ? 'Saya' : $user->username);
        $data['subtitle'] = "Lihat detail akun.";

        $data['profil'] = User::where('id', auth()->user()->id)->firstOrFail();

        return view('admin.profile.show', $data);
    }

    public function updatePhoto(Request $request)
    {
        allowOnlyAjax($request);

        try {
            $validated = $request->validate([
                'photo_profile' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $user = User::findOrFail(auth()->user()->id);

            DB::beginTransaction();

            // Hapus foto lama jika ada
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }

            // Update foto
            $user->update([
                'photo_profile' => Helper::storeFile($request->file('photo_profile'), 'images/profile')
            ]);

            Helper::createLog($user, 'updated', $this->logContext, 'mengubah foto profil', [], []);

            DB::commit();

            return JsonResponseHelper::success([
                'photo_url' => $user->photo_profile
            ], "Foto profil berhasil diubah.", 200);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);

            return JsonResponseHelper::error(
                'Gagal mengubah foto profil: ' . $e->getMessage(),
                JsonResponseHelper::$FAILED_UPDATE,
                500
            );
        }
    }

    public function updateProfil(Request $request)
    {
        allowOnlyAjax($request);

        try {
            $userId = auth()->user()->id;
            $user = auth()->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email:dns|unique:users,email,' . $userId,
            ]);

            $datauser = User::findOrFail($userId);

            DB::beginTransaction();

            // Update data user
            $datauser->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            Helper::createLog($datauser, 'updated', $this->logContext, 'mengubah profil', [], []);

            DB::commit();

            return JsonResponseHelper::success(null, "Profil berhasil diubah.", 200);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return JsonResponseHelper::error(
                'Data profil tidak ditemukan.',
                JsonResponseHelper::$FAILED_FIND,
                404
            );
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error(
                $e->getMessage(),
                JsonResponseHelper::$FAILED_UPDATE,
                500
            );
        }
    }

    public function updatePassword(Request $request)
    {
        allowOnlyAjax($request);

        $user = auth()->user()->id;

        $validated = $request->validate([
            'old_password' => 'required',
            'new_password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'confirm_new_password' => 'required|same:new_password',
        ]);

        $user = User::findOrFail($user);
        if (!Hash::check($validated['old_password'], $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => "Password lama salah."
            ]);
        }

        try {
            DB::beginTransaction();

            $user->password = bcrypt($validated['new_password']);
            $user->last_password_updated_ip = $request->ip();
            $user->last_password_updated_at = now();
            $user->last_password_updated_device = $request->userAgent();
            $user->save();

            Helper::createLog($user, 'updated', $this->logContext, 'mengubah password', [], []);

            DB::commit();

            return JsonResponseHelper::success(null, "Password berhasil diubah.", 200);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();

            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_UPDATE . " " . $e->getMessage());
        }
    }
}
