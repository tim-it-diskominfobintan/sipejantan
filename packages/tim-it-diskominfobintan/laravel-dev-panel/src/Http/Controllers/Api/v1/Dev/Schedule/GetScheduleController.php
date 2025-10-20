<?php

namespace TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Schedule;

use TimItDiskominfoBintan\DevPanel\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Throwable;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Kernel;
use ReflectionClass;
use TimItDiskominfoBintan\ProxyScheduler\Proxy\ProxyScheduler;

class GetScheduleController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $proxySchedule = new ProxyScheduler();
            $cache = $proxySchedule->getSchedules();

            // $data = [];
            // foreach ($cache as $command => $details) {
            //     $data[] = [
            //         'command' => $command,
            //         'enabled' => $details['enabled'] ?? true,
            //         'cron' => $details['cron'] ?? '* * * * *',
            //         'timezone' => $details['timezone'] ?? 'UTC',
            //         'description' => $details['description'] ?? 'No description available',
            //     ];
            // }

            $data = [];
            $schedule = app(Schedule::class);

            // Gunakan refleksi untuk panggil method protected schedule()
            $kernel = app(Kernel::class);
            $ref = new ReflectionClass($kernel);

            $method = $ref->getMethod('schedule');
            $method->setAccessible(true);
            $method->invoke($kernel, $schedule);

            if ($cache === null) {
                foreach ($schedule->events() as $event) {
                    $command = $event->command ?? $event->description;

                    if (!$command) continue;

                    $key = $this->_generateEventKey($event);

                    $data[] = [
                        'key' => $key,
                        'artisan_command' => $this->_extractArtisanCommand($command),
                        'command' => $command,
                        'enabled' => true,
                        'cron' => $event->expression,
                        'timezone' => $event->timezone ?? 'UTC',
                        'description' => $event->description,
                        'next_due' => $event->nextRunDate()?->toIso8601String() ?? '-',
                    ];
                }
            } else {
                foreach ($cache as $key => $details) {
                    // Cocokkan dengan event yang sesuai
                    $matchedEvent = collect($schedule->events())->first(function ($event) use ($details) {
                        $cmd = $event->command ?? $event->description;
                        return $this->_extractArtisanCommand($cmd) === $details['artisan_command']
                            && $event->expression === $details['cron']
                            && ($event->timezone ?? 'UTC') === ($details['timezone'] ?? 'UTC');
                    });

                    $data[] = [
                        'key' => $key,
                        'artisan_command' => $details['artisan_command'],
                        'command' => $details['command'] ?? $key,
                        'enabled' => $details['enabled'] ?? true,
                        'cron' => $details['cron'] ?? '* * * * *',
                        'timezone' => $details['timezone'] ?? 'UTC',
                        'description' => $details['description'] ?? 'No description available',
                        'next_due' => $matchedEvent?->nextRunDate()?->toIso8601String() ?? '-',
                    ];
                }
            }


            return JSONResponseHelper::success($data, JSONResponseHelper::$SUCCESS_INDEX . " Schedule cache retrieved successfully");
        } catch (Throwable $e) {
            report($e);

            return JSONResponseHelper::error($e->getMessage(), JSONResponseHelper::$FAILED_INDEX . " " . $e->getMessage());
        }
    }

    private function _extractArtisanCommand($commands)
    {
        if (preg_match("/'artisan'\s+(.*)/", $commands, $matches)) {
            return trim($matches[1]);
        }

        return trim($commands); // fallback
    }
}
