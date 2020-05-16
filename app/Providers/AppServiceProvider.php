<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services
     *
     * @return void
     */
    public function boot()
    {
        // add Str::currency macro
        Str::macro('currency', function ($price)
        {
            return number_format($price, 2, '.', '\'');
        });

        // add Str::integer macro
        Str::macro('integer', function ($integer)
        {
            return number_format($integer, 0, '.', '\'');
        });

        // add Str::percentage macro
        Str::macro('percentage', function ($percentage)
        {
            return sprintf('%+.2f%%', $percentage);
        });
    }
}
