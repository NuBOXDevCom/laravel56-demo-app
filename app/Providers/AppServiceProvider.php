<?php

namespace App\Providers;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if ('admin', function() {
            return auth()->check() && auth()->user()->role === 'admin';
        });
        Blade::if ('adminOrOwner', function($id) {
            return auth()->check() && (auth()->id() === $id || auth()->user()->role === 'admin');
        });
        AbstractPaginator::defaultView('pagination::bootstrap-4');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
