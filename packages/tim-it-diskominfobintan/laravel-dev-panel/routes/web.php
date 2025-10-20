<?php

use Illuminate\Support\Facades\Route;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\ActivityController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\DevDashboardController;

Route::middleware(['web', 'auth', 'role:superadmin|admin|developer'])->group(function () {
    Route::get('dev-panel', [DevDashboardController::class, 'index']);
    Route::get('dev-panel/activity-viewer ', [ActivityController::class, 'index']);
    Route::get('dev-panel/phpinfo', function () {
        return phpinfo();
    });
});

Route::get('/dev-panel/assets/{file}', function ($file) {
    $path = __DIR__ . "/../resources/js/{$file}";

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => getMimeTypeFromExtension(pathinfo($file, PATHINFO_EXTENSION)),
    ]);
})->where('file', '.*')->name('dev-panel.assets');
