<?php

use Illuminate\Support\Facades\Route;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Job\GetJobsController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Command\TriggerCommandController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Cron\GetLatestHeartbeatController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Cron\GetHistoryHeartbeatController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Maintenance\StopMaintenanceController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Maintenance\StartMaintenanceController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Maintenance\GetStatusMaintenanceController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Schedule\GetScheduleController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Schedule\ToggleScheduleController;
use TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Schedule\UpdateScheduleController;

Route::prefix('api/v1')
    ->middleware(['web', 'auth:sanctum', 'role:superadmin|admin|developer'])
    ->group(function () {
        Route::get('dev/cron/health', GetLatestHeartbeatController::class)->name('dev.cron.health');
        Route::get('dev/cron/history', GetHistoryHeartbeatController::class)->name('dev.cron.history');
        Route::get('dev/job', GetJobsController::class)->name('dev.job.index');
        Route::get('dev/maintenance', GetStatusMaintenanceController::class)->name('dev.maintenance.status');
        Route::patch('dev/maintenance/down', StartMaintenanceController::class)->name('dev.maintenance.down');
        Route::patch('dev/maintenance/up', StopMaintenanceController::class)->name('dev.maintenance.up');
        Route::post('dev/command', TriggerCommandController::class)->name('dev.command.trigger');
        Route::get('dev/schedule', GetScheduleController::class)->name('dev.schedule.get');
        Route::patch('dev/schedule/{id}/enabled', ToggleScheduleController::class)->name('dev.schedule.update.enabled');
        Route::put('dev/schedule/{id}', UpdateScheduleController::class)->name('dev.schedule.update');
    });
