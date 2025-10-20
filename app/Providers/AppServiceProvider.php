<?php

namespace App\Providers;

use App\Helpers\Helper;
use Illuminate\Pagination\Paginator;
use Spatie\Activitylog\ActivityLogger;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use TimItDiskominfoBintan\ProxyScheduler\Proxy\ProxyScheduler;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->_registerProxyScheduler();
        $this->_registerActivityLogMacro();
        Paginator::useBootstrap();
    }

    private function _registerProxyScheduler()
    {
        $proxySchedule = new ProxyScheduler();
        $cachePath = $proxySchedule->getCacheFilePath();

        if (!file_exists($cachePath)) {
            Artisan::call('schedule:list');
        }
    }

    private function _registerActivityLogMacro()
    {
        ActivityLogger::macro('withRequestInfo', function (array $extraProperties = []) {
            $requestInfo = [
                'ip' => request()->ip(),
                'user_agent' => [
                    ...Helper::detectUserAgent(request()->userAgent()),
                    'full' => request()->userAgent()
                ],
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'referer' => request()->headers->get('referer'),
                'x_forwarded_for' => request()->headers->get('x-forwarded-for'),
                'accept_language' => request()->headers->get('accept-language'),
                'method' => request()->method(),
                'url' => request()->fullUrl(),
                'payload' => request()->except(['password', '_token']),
                'user' => auth()->check() ? auth()->user() : null,
            ];

            return $this->withProperties(array_merge($requestInfo, $extraProperties));
        });
    }
}
