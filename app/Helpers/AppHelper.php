<?php 

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/*
    |--------------------------------------------------------------------------
    | Application Helper
    |--------------------------------------------------------------------------
    |
    | Ini khusus helper yang perlu class, harus use/import classnya dulu baru bisa dipakai.
    | Gunanya untuk load di controller, model, atau view. Contohnya: Helper::insertLog().
    | Kalo mau versi function aja, silahkan tambahkan di app/Helpers/GlobalHelpers.
    |
    |
*/

if (!function_exists('allowOnlyAjax')) {
    function allowOnlyAjax(Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }
        
        if (!$request->ajax()) {
            if (config('app.environment') === 'development') {
                throw new HttpException(403, 'Forbidden. Aksi ini hanya bisa diakses via AJAX.');
            } else {
                throw new HttpException(404, 'Not Found.');
            }
        }
    }
}

if (!function_exists('checkUrlMatched')) {
    function checkUrlMatched($comparingPath, $returnValue = true)
    {
        $currentPath = request()->path();

        if (Str::startsWith($currentPath, $comparingPath)) {
            return $returnValue;
        }

        return false;
    }
}

// mengubah dari seconds: 17321387123 -> 3 jam yang lalu
if (!function_exists('formatGMDateToHuman')) {
    function formatGMDateToHuman($totalSeconds)
    {
        // Kalau kurang dari 1 hari (86400 detik)
        if ($totalSeconds < 86400) {
            return gmdate('H:i:s', $totalSeconds);
        }

        $interval = CarbonInterval::seconds($totalSeconds)->cascade();

        $parts = [];
        if ($interval->y > 0) {
            $parts[] = $interval->y . ' tahun';
        }
        if ($interval->m > 0) {
            $parts[] = $interval->m . ' bulan';
        }
        if ($interval->d > 0) {
            $parts[] = $interval->d . ' hari';
        }
        if ($interval->h > 0) {
            $parts[] = $interval->h . ' jam';
        }
        if ($interval->i > 0) {
            $parts[] = $interval->i . ' menit';
        }

        return implode(' ', $parts);
    }
}

// mengubah dari format: 2025-01-31 14:00:00 -> 3 jam yang lalu
if (!function_exists('diffToHuman')) {
    function diffToHuman($datetime)
    {
        return Carbon::parse($datetime)->diffForHumans();
    }
}
