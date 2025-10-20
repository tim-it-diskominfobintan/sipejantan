<?php

namespace TimItDiskominfoBintan\DevPanel\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;
use TimItDiskominfoBintan\DevPanel\Models\ActivityLogBatch;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $logQuery = Activity::query()
                ->whereNotNull('batch_uuid');

            if ($request->filled('start_datetime')) {
                $logQuery->where('created_at', '>=', Carbon::parse($request->start_datetime)->startOfDay());
            }

            if ($request->filled('end_datetime')) {
                $logQuery->where('created_at', '<=', Carbon::parse($request->end_datetime)->endOfDay());
            }

            if ($request->filled('log_name')) {
                $logQuery->where('log_name', 'LIKE', '%' . $request->log_name . '%');
            }

            if ($request->filled('event')) {
                $logQuery->where('event', $request->event);
            }

            $filteredBatchUuids = $logQuery->pluck('batch_uuid')->unique();

            $data = ActivityLogBatch::with(['activities' => function ($q) {
                $q->orderBy('created_at'); // agar yang pertama muncul dulu
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

            if ($request->filled('start_datetime')) {
                $start = Carbon::parse($request->start_datetime)->startOfDay();
                $data->where('created_at', '>=', $start);
            }

            if ($request->filled('end_datetime')) {
                $end = Carbon::parse($request->end_datetime)->endOfDay();
                $data->where('created_at', '<=', $end);
            }

            if ($request->filled('log_name')) {
                $data->where('log_name', $request->log_name);
            }

            if ($request->filled('event')) {
                $data->where('event', $request->event);
            }

            $data = $data->filter();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-secondary-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id . '"><i class="bi bi-eye"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('subject_type', function ($row) {
                    return $row->subject_type . ' - ' . $row->subject_id;
                })
                ->addColumn('causer_type', function ($row) {
                    $properties = json_decode(json_encode($row->properties), false);
                    return $properties->user->username;
                })
                ->addColumn('properties', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    if (!empty($row->activities)) {
                        $batchDetails = $row->activities;
                        $batchDetails = htmlspecialchars(json_encode($batchDetails));
                    } else {
                        $batchDetails = htmlspecialchars(json_encode([]));
                    }

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .=  '<a href="javascript:void(0)" class="btn-show-properties btn-sm btn btn-outline-secondary shadow-none" data-batch_details="' . $batchDetails . '" data-detail="' . $detail . '" data-id="' . $row->id . '">Lihat</a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['subject_type', 'causer_type', 'properties', 'actions'])
                ->make(true);
        }

        return view('dev-panel::activity.index', [
            'log_events' => ['created', 'updated', 'deleted', 'viewed'],
        ]);
    }
}
