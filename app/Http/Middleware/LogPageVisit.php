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
        if (
            $request->method() !== 'GET' ||
            $request->expectsJson() ||
            $request->is('api/*') ||
            $request->is('livewire/*') ||
            $request->is('storage/*')
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
                'created_by'         => auth()->id(), // null for guests
                'activity_type'      => 'page_visit',
                'dashboard_activity' => 'visited a page',
                'activity_desc'      => 'visited ' . $request->fullUrl(),
                'activity_date'      => now()->format('Y-m-d H:i:s'),
                'subject_type'       => 'page',
                'subject_id'         => null,
                'ip_address'         => $request->ip(),
            ]);
        });

        return $next($request);
    }
}
