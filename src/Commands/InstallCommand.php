<?php

namespace Flobbos\LaravelSystemInfo\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'laravel-system-info:install';

    protected $description = 'Install Laravel System Info package';

    public function handle()
    {
        $token = Str::random(32);
        $this->info('Generated API token: ' . $token);

        // Add to .env
        $this->appendToEnv('SYSTEM_INFO_TOKEN=' . $token);

        $this->call('vendor:publish', ['--provider' => 'Flobbos\\LaravelSystemInfo\\LaravelSystemInfoServiceProvider', '--tag' => 'laravel-system-info-config']);

        $this->info('Package installed successfully. Copy the token to ServerSync.');
    }

    protected function appendToEnv($line)
    {
        $path = base_path('.env');
        file_put_contents($path, PHP_EOL . $line, FILE_APPEND);
    }
}
