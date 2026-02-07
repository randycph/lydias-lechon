<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;

class LogPageVisit
{
    public function handle(Request $request, Closure $next)
    {
        // Only log real page visits
        // exclude css/js/img/font files
        if (
            $request->method() !== 'GET' ||
            $request->expectsJson() ||
            $request->is('api/*') ||
            $request->is('livewire/*') ||
            $request->is('storage/*') ||
            $request->is('*/css/*') ||
            $request->is('*/js/*') ||
            $request->is('*/img/*') ||
            $request->is('*/fonts/*') ||
            $request->is('get-carts') ||
            $request->is('cart-count') ||
            $request->is('get-carts') || 
            $request->is('display-added-payments')
        ) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 5-Second Deduplication
        |--------------------------------------------------------------------------
        | Same IP + URL + Session within 5 seconds = skip
        */
        $dedupeKey = 'page_visit:' . sha1(
            ($request->ip() ?? 'ip') . '|' .
            ($request->fullUrl()) . '|' .
            ($request->session()->getId() ?? 'session')
        );

        if (!Cache::add($dedupeKey, true, now()->addSeconds(5))) {
            return $next($request);
        }

        ActivityLog::withoutEvents(function () use ($request) {
            ActivityLog::create([
                'created_by' => $this->resolveActorId($request),
                'activity_type'      => 'page_visit',
                'dashboard_activity' => 'visited a page',
                'activity_desc'      => 'visited ' . $request->fullUrl(),
                'activity_date'      => now()->format('Y-m-d H:i:s'),
                'subject_type'       => 'page',
                'subject_id'         => null,
                'ip_address'         => $request->ip(),
                'email'              => auth()->check() ? auth()->user()?->email : null,
                'role'               => auth()->check() ? auth()->user()?->user_role?->name : null,
                'session_id'         => $request->session()->getId(),
            ]);
        });

        return $next($request);
    }

    protected function resolveActorId(Request $request): ?int
    {
        if (auth()->check()) {
            return auth()->id();
        }

        return ActivityLog::where('session_id', $request->session()->getId())
            ->whereNotNull('created_by')
            ->latest('id')
            ->value('created_by');
    }
}
