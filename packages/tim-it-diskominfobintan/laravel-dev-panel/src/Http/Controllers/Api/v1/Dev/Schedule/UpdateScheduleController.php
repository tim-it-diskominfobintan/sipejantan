<?php

namespace TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Schedule;

use TimItDiskominfoBintan\DevPanel\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Throwable;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Kernel;
use Illuminate\Validation\ValidationException;
use ReflectionClass;
use TimItDiskominfoBintan\ProxyScheduler\Proxy\ProxyScheduler;

class UpdateScheduleController extends Controller
{
    private $schedule;

    public function __construct()
    {
        $this->schedule = new Schedule();
    }

    public function __invoke(Request $request, $id)
    {
        try {
            $request->validate([
                'cron' => 'required',
            ]);

            // udpate
            $proxySchedule = new ProxyScheduler($this->schedule);
            $cache = $proxySchedule->getSchedules();

            $key = $id;
            $findSchedule = array_filter($cache, function ($value, $cacheKey) use ($key) {
                return $cacheKey === $key;
            }, ARRAY_FILTER_USE_BOTH);

            if ($findSchedule) {
                $getKey = array_keys($findSchedule)[0];
                $cache[$getKey]['cron'] = $request->cron;

                // simpan cache
                file_put_contents($proxySchedule->getCacheFilePath(), json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                // update proxy
                $proxySchedule->updateProxy();

                return JSONResponseHelper::success($cache[$getKey], JSONResponseHelper::$SUCCESS_UPDATE);
            } else {
                return JSONResponseHelper::success([], 'No updated data, please check your controller.');
            }

            // return JSONResponseHelper::success($data, JSONResponseHelper::$SUCCESS_INDEX . " Schedule cache retrieved successfully");
        } catch (ValidationException $e) {
            return JSONResponseHelper::error($e->errors(), JSONResponseHelper::$FAILED_VALIDATE . " " . $e->getMessage(), 422);
        } catch (Throwable $e) {
            report($e);

            return JSONResponseHelper::error($e->getMessage(), JSONResponseHelper::$FAILED_INDEX . " " . $e->getMessage());
        }
    }
}
