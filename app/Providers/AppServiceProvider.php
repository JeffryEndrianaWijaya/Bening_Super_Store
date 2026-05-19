<?php

namespace App\Providers;

use App\View\Layouts\CustomerLayout;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Layouts\DashboardLayout;
use App\View\Components\Dialog;
use App\View\Components\Dialog\HeaderDialog;
use App\View\Components\Dialog\ContentDialog;
use App\View\Components\Dialog\Dialog as ComponentsDialog;
use App\View\Components\Dialog\FooterDialog;
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

        Blade::component('dashboard-layout', DashboardLayout::class);
        Blade::component('customer-layout', CustomerLayout::class);
        //Blade::component('header-dialog', HeaderDialog::class);
        //Blade::component('content-dialog',ContentDialog::class);
        //Blade::component('footer-dialog', FooterDialog::class);
        Blade::component('dialog', ComponentsDialog::class);
    }
}
