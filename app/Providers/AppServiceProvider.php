<?php

namespace App\Providers;

use App\Contracts\Services\ExportOrdersServiceInterface;
use App\Services\CSVOrdersExportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ExportOrdersServiceInterface::class, CSVOrdersExportService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
