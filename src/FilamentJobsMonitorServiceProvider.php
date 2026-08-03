<?php

namespace Croustibat\FilamentJobsMonitor;

use Croustibat\FilamentJobsMonitor\Commands\PruneQueueMonitorCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentJobsMonitorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-jobs-monitor';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigrations([
                'create_filament-jobs-monitor_table',
                'add_failures_to_filament-jobs-monitor_table',
            ])
            ->hasCommand(PruneQueueMonitorCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FilamentJobsMonitorPlugin::class);
    }

    /**
     * The bundled stylesheet is deliberately NOT registered here.
     *
     * `FilamentAsset::register()` is global: an asset registered in `packageBooted()`
     * is injected into every page of every panel, even panels that never register
     * this plugin, and it renders after the panel theme. Because the bundle is a
     * plain Tailwind utility dump, its single-class selectors then win the cascade
     * and redefine the host application's `ring-*`, `gray-*` and `primary-*`
     * utilities app-wide.
     *
     * Filament's own guidance is that plugins should let the host application's
     * theme compile their views via `@source`, and that assets which load on every
     * page belong on the panel instead. Both routes are supported here — see
     * `FilamentJobsMonitorPlugin::withStyles()` and the README.
     *
     * @see https://filamentphp.com/docs/5.x/advanced/assets#using-tailwind-css-in-plugins
     */
    public function packageBooted(): void
    {
        //
    }
}
