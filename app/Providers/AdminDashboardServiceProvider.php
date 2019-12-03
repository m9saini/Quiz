<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\ViewComposers\AdminDashboardComposer;

class AdminDashboardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        AdminDashboardComposer::dashboardCount();
        AdminDashboardComposer::activeRoles();
        AdminDashboardComposer::latesUser();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
