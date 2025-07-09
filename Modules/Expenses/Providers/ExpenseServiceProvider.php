<?php

namespace Modules\Expenses\Providers;

use Modules\Expenses\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;

class ExpenseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . "/../Database/Migrations");
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'expense');
    }
}
