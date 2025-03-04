<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (\App::environment() === 'production') {
            \URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(strpos(url()->current(), "storage") === FALSE && strpos(url()->current(), "theme") === FALSE){
            $insert_logs = \App\ActivityLog::create([
                'created_by' => \Auth::id() ?? 'guest',
                'activity_type' => 'visit',
                'dashboard_activity' => 'visit page',
                'activity_desc' => \Request::ip(),
                'activity_date' => date('Y-m-d H:i:s'),
                'reference' => url()->current()
            ]);
        }
        Paginator::defaultView('vendor.pagination.default');
        Blade::component('components.error', 'hasError');
    }
}
