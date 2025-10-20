<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeTheme extends Command
{
    protected $signature = 'make:theme {name}';
    protected $description = 'Copy theme stub to resources/views';

    public function handle()
    {
        $name = $this->argument('name');

        $stubPath = resource_path("stubs/themes/{$name}");
        $viewPath = resource_path("views");

        if (!File::exists($stubPath)) {
            $this->error("Theme stub '{$name}' not found.");
            return;
        }

        $this->info("Copying theme '{$name}' to views...");

        // Rekursif copy folder & ubah .stub ke .blade.php
        $this->copyStubDirectory($stubPath, $viewPath);

        $this->info("Theme '{$name}' successfully generated.");
    }

    protected function copyStubDirectory($from, $to)
    {
        foreach (File::allFiles($from) as $file) {
            $relativePath = str_replace($from . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $targetPath = $to . DIRECTORY_SEPARATOR . preg_replace('/\.stub$/', '.blade.php', $relativePath);

            File::ensureDirectoryExists(dirname($targetPath));
            File::copy($file->getPathname(), $targetPath);
        }
    }
}
