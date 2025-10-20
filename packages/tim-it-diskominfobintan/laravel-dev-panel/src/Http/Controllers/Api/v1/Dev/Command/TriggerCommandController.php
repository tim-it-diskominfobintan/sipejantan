<?php

namespace TimItDiskominfoBintan\DevPanel\Http\Controllers\Api\v1\Dev\Command;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Throwable;
use TimItDiskominfoBintan\DevPanel\Helpers\JsonResponseHelper;

class TriggerCommandController extends Controller
{
    public function __invoke(Request $request)
    {
       try {
            $outputs = [];
            $commands = $request->input('commands', []);
            $blackListCommands = [
                'migrate', // Migrate database
                'migrate:fresh', // Fresh migration
                'migrate:rollback', // Rollback migration
                'db:seed', // Seed database
                'db:wipe', // Wipe database
            ];

            if (empty($commands)) {
                throw new \Exception("Commands tidak disertakan!");
            }

            foreach ($commands as $command) {
                if (in_array($command, $blackListCommands)) {
                    throw new \Exception("Command $command tidak diizinkan untuk dijalankan!");
                } else {
                    
                    if (!collect(Artisan::all())->has($command)) {
                        throw new \Exception("Command $command tidak ditemukan!");
                    }

                    Artisan::call($command);
                    $run = Artisan::output();
                    array_push($outputs, [
                        'command' => $command,
                        'output' => $run
                    ]);
                }
            }

            return JsonResponseHelper::success($outputs, 
            count($commands) . " command berhasil dijalankan.");
        } catch (Throwable $e) {
            report($e);

            return JSONResponseHelper::error($e->getMessage(), JSONResponseHelper::$FAILED_INDEX . " " . $e->getMessage());
        }
    }
}
