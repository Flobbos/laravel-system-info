<?php

namespace Flobbos\LaravelSystemInfo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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

    protected function runComposerCommand($command)
    {
        Artisan::call('shell:exec', ['command' => "composer $command --no-interaction"]);
        $output = Artisan::output();
        return json_decode($output, true);
    }
}
