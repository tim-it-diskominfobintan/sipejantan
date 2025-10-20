<?php

namespace TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Cron;

use TimItDiskominfoBintan\DevPanel\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class GetHistoryHeartbeatController extends Controller
{
    public function __invoke()
    {
        try {
            $timePath = storage_path('logs/cron-heartbeat.log');

            if (!file_exists($timePath)) {
                return JsonResponseHelper::success(null);
            }

            $history = file($timePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            $now = time();
            $sevenDaysAgo = strtotime('-7 days');
            $grouped = [];
            $anomaliesByDate = [];

            foreach ($history as $H) {
                $tsTime = strtotime($H);

                if ($tsTime >= $sevenDaysAgo && $tsTime <= $now) {
                    $dateKey = date('Y-m-d', $tsTime);
                    $grouped[$dateKey][] = $H;
                }
            }

            ksort($grouped);

            // Deteksi anomali per hari
            foreach ($grouped as $date => $timestamps) {
                $prev = null;
                foreach ($timestamps as $timestamp) {
                    $current = strtotime($timestamp);

                    if ($prev !== null) {
                        $diff = $current - $prev;
                        if ($diff > 180) { // lebih dari 3 menit
                            $anomaliesByDate[$date][] = [
                                'from' => date('Y-m-d H:i:s', $prev),
                                'to' => $timestamp,
                                'gap_minutes' => round($diff / 60, 2),
                            ];
                        }
                    }
                    $prev = $current;
                }
            }

            return JSONResponseHelper::success([
                'grouped_heartbeat' => $grouped,
                'anomalies' => $anomaliesByDate,
            ]);
        } catch (Throwable $e) {
            report($e);

            return JSONResponseHelper::error($e->getMessage(), JSONResponseHelper::$FAILED_INDEX . " " . $e->getMessage());
        }
    }
}
