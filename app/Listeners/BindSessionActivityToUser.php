<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\ActivityLog;

class BindSessionActivityToUser
{
    public function handle(Login $event): void
    {
        $sessionId = request()->session()->getId();

        ActivityLog::where('session_id', $sessionId)
            ->whereNull('created_by')
            ->update([
                'created_by' => $event->user->id,
                'email'      => $event->user->email,
                'role'       => optional($event->user->user_role)->name,
            ]);
    }
}
