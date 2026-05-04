<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Locale Bahasa Indonesia untuk Carbon
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID');

        // Pagination pakai Bootstrap 5
        Paginator::useBootstrapFive();
    }
}
