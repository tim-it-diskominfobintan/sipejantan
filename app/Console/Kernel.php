<?php

namespace App\Console;

use ReflectionClass;    
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CleanExpiredUserSessions;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use TimItDiskominfoBintan\ProxyScheduler\Proxy\ProxyScheduler;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $cleanExpiredCommand = new CleanExpiredUserSessions();

        // DEFINISIKAN SEMUA SCHEDULE DISINI
        $schedule->command($cleanExpiredCommand->signature)
            ->description($cleanExpiredCommand->description)
            ->everyTenMinutes()
            ->withoutOverlapping();

        // SCHEDULE PROXY (JANGAN DIGANGGU)
        // $this->_registerProxy($schedule);
        $proxy = new ProxyScheduler($schedule);
        $proxy->registerProxy();
    }

    protected function commands()
    {
        $this->_getCommands();

        require base_path('routes/console.php');
    }

    private function _getCommands()
    {
        $this->load(__DIR__ . '/Commands');
    }

    // private function _registerProxy(Schedule $schedule)
    // {
    //     $cachePath = storage_path('framework/schedule-cache.json');

    //     if (!file_exists($cachePath)) {
    //         $cache = [];

    //         foreach ($schedule->events() as $event) {
    //             // ambil deskripsi jika larvel tidak memiliki command
    //             $command = $event->command ?? $event->description;

    //             // skip jika tidak ada command
    //             if (!$command) continue;

    //             $cache[$command] = [
    //                 'enabled' => true,
    //                 'cron' => $event->expression,
    //                 'timezone' => $event->timezone,
    //                 'description' => $event->description,
    //             ];
    //         }

    //         // simpan cache
    //         file_put_contents($cachePath, json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    //     } else {
    //         $newCache = [];
    //         $oldCache = json_decode(file_get_contents($cachePath), true);

    //         // sync dengan command yang ada pada packages project
    //         foreach ($schedule->events() as $event) {
    //             // ambil deskripsi jika larvel tidak memiliki command
    //             $command = $event->command ?? $event->description;

    //             // skip jika tidak ada command
    //             if (!$command) continue;

    //             if (!isset($oldCache[$command])) {
    //                 // jika command tidak ada di cache lama, buat entri baru
    //                 $newCache[$command] = [
    //                     'enabled' => $oldCache[$command]['enabled'] ?? true, // gunakan nilai lama jika ada
    //                     'cron' => $oldCache[$command]['cron'] ?? $event->expression,
    //                     'timezone' => $oldCache[$command]['timezone'] ?? $event->timezone,
    //                     'description' => $oldCache[$command]['description'] ?? $event->description,
    //                 ];
    //             }
    //         }

    //         if (count($newCache) > 0) {
    //             // simpan cache baru
    //             file_put_contents($cachePath, json_encode($newCache, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    //         }
    //     }

    //     // INI UNTUK OVVERRIDE SCHEDULE
    //     $config = json_decode(file_get_contents($cachePath), true);
    //     foreach ($schedule->events() as $key => $event) {
    //         // ambil deskripsi jika larvel tidak memiliki command
    //         $command = $event->command ?? $event->description;

    //         // skip jika tidak ada command
    //         if (!isset($config[$command])) continue;

    //         // pengcekan command aktif atau tidak
    //         if (!$config[$command]['enabled']) {
    //             // skip jika tidak aktif
    //             $event->skip(function () {
    //                 return true;
    //             });
    //         } else {
    //             // override cron dan timezone (TEMPAT OVERRIDE SCHEDULE)
    //             $event->expression = $config[$command]['cron'];
    //             $event->timezone = $config[$command]['timezone'];
    //         }
    //     }

    //     // INI UNTUK REMOVE SCHEDULE YANG DIMATIKAN
    //     // Ambil daftar event
    //     $events = $schedule->events();

    //     // Filter berdasarkan cache `enabled`
    //     $filteredEvents = collect($events)->filter(function ($event) use ($config) {
    //         $command = $event->command ?? $event->description;

    //         if (!$command) return true;

    //         // Jika command dimatikan, exclude dari schedule
    //         if (isset($config[$command]) && $config[$command]['enabled'] === false) {
    //             return false;
    //         }

    //         return true;
    //     })
    //     ->values()
    //     ->all();

    //     // Gunakan ReflectionClass untuk ganti property protected $events
    //     $reflection = new ReflectionClass($schedule);
    //     $property = $reflection->getProperty('events');
    //     $property->setAccessible(true);
    //     $property->setValue($schedule, $filteredEvents);
    // }
}
