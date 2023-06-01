<?php

namespace Mahmoudmhamed\LaravelSlackErrorsLog;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Mahmoudmhamed\LaravelSlackErrorsLog\Commands\LaravelSlackErrorsLogCommand;

class LaravelSlackErrorsLogServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/slack-errors-log.php' => config_path('slack-errors-log.php'),
        ], 'laravel-slack-errors-log-config');
    }
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-slack-errors-log')
            ->hasConfigFile();
    }
}
