<?php

namespace TimItDiskominfoBintan\DevPanel;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Psy\Readline\Hoa\Console;
use TimItDiskominfoBintan\DevPanel\Console\Commands\CronHeartbeat;
use TimItDiskominfoBintan\ProxyScheduler\Proxy\ProxyScheduler;

class DevPanelServiceProvider extends ServiceProvider
{
    private string $name = 'dev-panel';

    public static function basePath($path)
    {
        return __DIR__ . '' . $path;
    }

    public function boot()
    {
        // Publish view, config, migrations
        // $this->publishes([
        //     self::basePath("/config" . "/" . $this->name . ".php") => config_path($this->name . ".php"),
        // ], $this->name . "-config");

        // register commands
        $this->commands([
            CronHeartbeat::class,
        ]);

        // register schedule
        // Artisan::call('schedule:list');
        $this->app->afterResolving(\Illuminate\Console\Scheduling\Schedule::class, function ($schedule) {
            $cronHeartbeatCommand = new CronHeartbeat();
            $schedule->command($cronHeartbeatCommand->signature)
                ->description($cronHeartbeatCommand->description)
                ->withoutOverlapping();

            $proxy = new ProxyScheduler($schedule);
            $proxy->registerProxy();
        });

        // load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dev-panel');

        // Publish asset JS (untuk menjalakan php artisan vendor:publish)
        $this->publishes([
            __DIR__ . '/../resources/js' => public_path('vendor/laravel-dev-panel'),
        ], 'devpanel-assets');

        // Publish Migrations (untuk menjalakan php artisan vendor:publish)
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'devpanel-migrations');

        $this->app->register(\Yajra\DataTables\DataTablesServiceProvider::class);
    }

    public function register()
    {
        // load config file
        // $this->mergeConfigFrom(self::basePath("/config" . "/" . $this->name . ".php"), $this->name);
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

    //         // sync dengan command yang ada pada parent project
    //         foreach ($schedule->events() as $event) {
    //             // ambil deskripsi jika larvel tidak memiliki command
    //             $command = $event->command ?? $event->description;

    //             // skip jika tidak ada command
    //             if (!$command) continue;

    //             $newCache[$command] = [
    //                 'enabled' => $oldCache[$command]['enabled'] ?? true, // gunakan nilai lama jika ada
    //                 'cron' => $oldCache[$command]['cron'] ?? $event->expression,
    //                 'timezone' => $oldCache[$command]['timezone'] ?? $event->timezone,
    //                 'description' => $oldCache[$command]['description'] ?? $event->description,
    //             ];
    //         }

    //         if (count($newCache) > 0) {
    //             // simpan cache baru
    //             file_put_contents($cachePath, json_encode($newCache, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    //         }
    //     }

    //     // ambil cache
    //     $config = json_decode(file_get_contents($cachePath), true);
    //     foreach ($schedule->events() as $key => $event) {
    //         // ambil deskripsi jika larvel tidak memiliki command
    //         $command = $event->command ?? $event->description;

    //         // skip jika sudah ada di cache
    //         if (!isset($config[$command])) continue;

    //         // pengcekan command aktif atau tidak
    //         if (!$config[$command]['enabled']) {
    //             // skip jika tidak aktif
    //             unset($schedule->events()[$key]);

    //             $event->skip(function () {
    //                 return true;
    //             });
    //         } else {
    //             // override cron dan timezone (TEMPAT OVERRIDE SCHEDULE)
    //             $event->expression = $config[$command]['cron'];
    //             $event->timezone = $config[$command]['timezone'];
    //         }
    //     }
    // }
}
