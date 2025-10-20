<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use TimItDiskominfoBintan\DevPanel\Models\ActivityLogBatch;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
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

        return view('admin.profile.show', $data);
    }
}
