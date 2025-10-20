<?php

namespace App\Listeners;

use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogScheduledTaskSuccess
{
    public function __construct()
    {
        //
    }

    public function handle(ScheduledTaskFinished $event)
    {
        Log::channel('schedule')->info("✅ Task succeeded: " . $event->task->description);
    }
}
