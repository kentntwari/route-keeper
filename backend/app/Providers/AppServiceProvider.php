<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $command = $_SERVER['argv'][1] ?? null;

        if (! is_string($command) || ! $this->shouldUseDirectDatabaseConnection($command)) {
            return;
        }

        Config::set('database.default', 'pgsql_direct');
        DB::purge('pgsql_direct');
    }

    private function shouldUseDirectDatabaseConnection(string $command): bool
    {
        return in_array($command, [
            'db:wipe',
            'migrate',
            'migrate:fresh',
            'migrate:install',
            'migrate:refresh',
            'migrate:reset',
            'migrate:rollback',
            'migrate:status',
            'schema:dump',
        ], true);
    }
}
