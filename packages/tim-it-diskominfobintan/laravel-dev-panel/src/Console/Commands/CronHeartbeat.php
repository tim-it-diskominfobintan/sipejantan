<?php

namespace TimItDiskominfoBintan\DevPanel\Console\Commands;

use Illuminate\Console\Command;

class CronHeartbeat extends Command
{
    public $signature = 'cron:heartbeat';
    public $description = 'Menandai bahwa cron scheduler masih berjalan';

    public function handle()
    {
        $now = now();
        $env = config('app.env');
        $new_log = "[{$now}] $env.INFO: Heartbeat at {$now}";

        // Path ke file
        $privatePath = storage_path('app/private/cron-log/latest-cron-heartbeat.txt');
        $logPath     = storage_path('logs/cron-heartbeat.log');

        // Pastikan direktori ada
        @mkdir(dirname($privatePath), 0755, true);
        @mkdir(dirname($logPath), 0755, true);

        // Tulis heartbeat timestamp
        file_put_contents($privatePath, $now);

        // Tambah baris log
        file_put_contents($logPath, $new_log . "\n", FILE_APPEND);

        $this->info('Heartbeat updated: ' . $now);
    }
}
