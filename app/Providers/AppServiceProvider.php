<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (App::environment() === 'production') {
            URL::forceScheme('https');
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
        if (app()->runningInConsole() || app()->environment('testing')) {
            return;
        }

        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        if(strpos(url()->current(), "storage") === FALSE && strpos(url()->current(), "theme") === FALSE){
            $insert_logs = \App\Models\ActivityLog::create([
                'created_by' => auth()->check() ? auth()->id() : 'guest',
                'activity_type' => 'visit',
                'dashboard_activity' => 'visit page',
                'activity_desc' => \Request::ip(),
                'activity_date' => date('Y-m-d H:i:s'),
                'reference' => url()->current()
            ]);
        }
        Paginator::defaultView('vendor.pagination.default');
        Blade::component('components.error', 'hasError');
        View::share('errors', session()->get('errors', new ViewErrorBag));
        View::share('globalAnalytics', optional(Setting::first())->google_analytics);
    }
}
