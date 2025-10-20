<?php

namespace TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Cron;

use TimItDiskominfoBintan\DevPanel\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Throwable;

class GetLatestHeartbeatController extends Controller
{
    public function __invoke()
    {
        try {
            $timePath = storage_path('app/private/cron-log/latest-cron-heartbeat.txt');

            if (!file_exists($timePath)) {
                return JSONResponseHelper::success([
                    'status_service' => 'inactive',
                    'last_heartbeat' => null
                ]);
            }

            $latest = file_get_contents($timePath);

            return JSONResponseHelper::success([
                'status_service' => (time() - strtotime($latest)) < (60 * 2) ? 'active' : 'inactive',
                'last_heartbeat' => $latest
            ]);
        } catch (Throwable $e) {
            report($e);

            return JSONResponseHelper::error($e->getMessage(), JSONResponseHelper::$FAILED_INDEX . " " . $e->getMessage());
        }
    }
}
