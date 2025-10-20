<?php

namespace TimItDiskominfoBintan\DevPanel\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;

class DevDashboardController extends Controller
{
    public function index()
    {
        $jobs = [];
        $logFiles = [];


        $logPath = storage_path('logs');
        $files = array_diff(scandir($logPath), ['.', '..']);
        foreach ($files as $file) {
            if (substr($file, -4) === '.log') {
                $logFiles[] = $file;
            }
        }

        if (!Schema::hasTable('jobs')) {
            $jobs = [];
        } else {
            $jobs = [];
        }

        $artisanCommands = [
            [
                'command' => 'view:cache',
                'description' => "Compile all of the application's Blade templates"
            ],
            [
                'command' => 'view:clear',
                'description' => "Clear all compiled view files"
            ],
            [
                'command' => 'cache:clear',
                'description' => "Flush the application cache"
            ],
            [
                'command' => 'config:cache',
                'description' => "Create a cache file for faster configuration loading"
            ]
        ];
        $artisanCommands = json_decode(json_encode($artisanCommands), false);

        return view('dev-panel::dashboard.index', [
            'count_log_files' => count($logFiles),
            'artisan_commands' => $artisanCommands,
            'jobs' => $jobs
        ]);
    }
}
