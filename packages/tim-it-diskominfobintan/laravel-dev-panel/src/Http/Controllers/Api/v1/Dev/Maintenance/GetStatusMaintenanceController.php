<?php

namespace TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Maintenance;

use TimItDiskominfoBintan\DevPanel\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class GetStatusMaintenanceController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $file_maintenance = storage_path('framework/down');

            if (!file_exists($file_maintenance)) {
                return JSONResponseHelper::success([
                    'status_maintenance' => false
                ]);
            }

            $down_at = filemtime($file_maintenance);

            return JSONResponseHelper::success([
                'status_maintenance' => true,
                'down_at' => date('Y-m-d H:i:s', $down_at)
            ]);
        } catch (Throwable $e) {
            report($e);

            return JSONResponseHelper::error($e->getMessage(), JSONResponseHelper::$FAILED_INDEX . " " . $e->getMessage());
        }
    }
}
