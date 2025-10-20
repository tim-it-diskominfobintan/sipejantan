<?php

namespace TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Job;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use TimItDiskominfoBintan\DevPanel\Helpers\JsonResponseHelper;

class GetJobsController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $job_connection = config('queue');
            $queue = $this->_getQueueJobs();

            return JSONResponseHelper::success([
                'job_connection' => $job_connection,
                'queue' => $queue
            ]);
        } catch (Throwable $e) {
            report($e);

            return JSONResponseHelper::error($e->getMessage(), JSONResponseHelper::$FAILED_INDEX . " " . $e->getMessage());
        }
    }

    private function _getQueueJobs($queueName = 'default')
    {
        $driver = config('queue.default');

        if ($driver === 'null') {
            return collect([
                'message' => 'Driver "null" tidak menyimpan atau menjalankan job.',
                'jobs' => [],
                'failed_jobs' => []
            ]);
        }

        if ($driver === 'sync') {
            return collect([
                'message' => 'Driver "sync" tidak menyimpan job dalam antrian. Job langsung dieksekusi.',
                'jobs' => [],
                'failed_jobs' => []
            ]);
        }

        if ($driver === 'database') {
            $queue = DB::table('jobs')
                ->where('queue', $queueName)
                ->get();
            $failed_jobs = $this->_getFailedJobs();

            return collect([
                'message' => "{$queue->count()} job dalam antrian, {$failed_jobs->count()} gagal.",
                'jobs' => $queue,
                'failed_jobs' => $failed_jobs
            ]);
        }

        if ($driver === 'redis') {
            // Ambil dari Redis list
            $key = 'queues:' . $queueName;
            $jobs = Redis::connection()->lrange($key, 0, -1);
            $failed_jobs = $this->_getFailedJobs();

            return collect([
                'message' => "{collect($jobs)->count()} job dalam antrian, {$failed_jobs->count()} gagal.",
                'jobs' => collect($jobs)->map(function ($raw) {
                    $payload = json_decode($raw, true);
                    return $payload['displayName'] ?? 'Unknown Job';
                }),
                'failed_jobs' => $failed_jobs
            ]);
        }

        // Tambahan untuk driver lain bisa dibuat di sini...

        return collect([
            'message' => "Driver \"$driver\" tidak dikenali atau belum didukung.",
        ]);
    }

    private function _getFailedJobs()
{
    return DB::table('failed_jobs')->get()->map(function ($job) {
        $payload = json_decode($job->payload, true);
        return [
            'id' => $job->id,
            'job' => $payload['displayName'] ?? 'Unknown Job',
            'queue' => $job->queue,
            'failed_at' => $job->failed_at,
            'exception' => Str::limit($job->exception, 200),
        ];
    });
}
}
