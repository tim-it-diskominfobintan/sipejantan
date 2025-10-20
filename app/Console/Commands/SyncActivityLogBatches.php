<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class SyncActivityLogBatches extends Command
{
    protected $signature = 'log:sync-batches';

    protected $description = 'Sync batch_uuid from activity_log to activity_log_batches summary table';

    public function handle(): int
    {
        $this->info('⏳ Menyinkronkan batch log...');

        // Ambil semua batch_uuid yang ada di activity_log
        $batches = Activity::select('batch_uuid')
            ->whereNotNull('batch_uuid')
            ->groupBy('batch_uuid')
            ->get();

        $this->info("🔍 Ditemukan {$batches->count()} batch untuk disinkronkan...");

        $insertCount = 0;

        foreach ($batches as $batch) {
            $batchId = $batch->batch_uuid;

            // Skip kalau sudah ada di tabel summary
            $exists = DB::table('activity_log_batches')->where('batch_uuid', $batchId)->exists();
            if ($exists) continue;

            // Hitung data summary-nya
            $logs = Activity::where('batch_uuid', $batchId);
            $count = $logs->count();
            $latest = $logs->max('created_at');

            // Insert ke summary table
            DB::table('activity_log_batches')->insert([
                'batch_uuid' => $batchId,
                'log_count' => $count,
                'latest_log_at' => $latest,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->line("✅ Batch {$batchId} disinkronkan ({$count} log)");

            $insertCount++;
        }

        $this->info("🎉 Sinkronisasi selesai. Total batch baru: {$insertCount}");

        return 0;
    }
}
