<?php

namespace Flobbos\LaravelSystemInfo;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class SystemInfoController
{
    public function index()
    {
        $composerShow = $this->runComposerCommand('show --format=json');
        $composerOutdated = $this->runComposerCommand('outdated --format=json');

        return [
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'installed_packages' => $composerShow ?? [],
            'outdated_packages' => $composerOutdated ?? [],
        ];
    }

    protected function runComposerCommand(string $command)
    {
        $composerPath = $this->getComposerPath();
        $fullCommand = $composerPath . ' ' . $command . ' --no-interaction';

        $process = new Process(explode(' ', $fullCommand)); // Split for Process
        $process->setWorkingDirectory(base_path());
        $process->setEnv([
            'COMPOSER_HOME' => storage_path('composer'),
            'HOME' => storage_path('composer'),
        ]);
        $process->run();

        if ($process->isSuccessful()) {
            $output = json_decode($process->getOutput(), true);
            return is_array($output) ? $output : [];
        }

        return [];
    }

    protected function getComposerPath()
    {
        $paths = [
            '/usr/local/bin/composer',
            '/usr/bin/composer',
            base_path('composer'),
        ];

        foreach ($paths as $path) {
            if (file_exists($path) && is_executable($path)) {
                Log::debug('Found Composer path', ['path' => $path]);
                return $path;
            }
        }

        return 'composer';
    }
}
