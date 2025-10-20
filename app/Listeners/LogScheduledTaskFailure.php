<?php

namespace App\Listeners;

use Illuminate\Console\Events\ScheduledTaskFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogScheduledTaskFailure
{
    public function __construct()
    {
        //
    }

    public function handle(ScheduledTaskFailed $event)
    {
        Log::channel('schedule')->error("❌ Task failed: " . $event->task->description);
        Log::channel('schedule')->error("Error: " . $event->exception->getMessage());
    }
}
