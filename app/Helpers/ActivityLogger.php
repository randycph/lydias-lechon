<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log($type, $desc, $table, $old = null, $new = null, $reference = null, $subject = null)
    {
        $user = auth()->user();

        ActivityLog::create([
            'created_by' => $user?->id,
            'activity_type' => $type,
            'dashboard_activity' => $desc,
            'activity_desc' => $desc,
            'activity_date' => now(),
            'db_table' => $table,
            'old_value' => $old ? json_encode($old) : null,
            'new_value' => $new ? json_encode($new) : null,
            'reference' => $reference,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'ip_address' => request()->ip(),
            'role' => $user?->role,
            'email' => $user?->email,
            'session_id' => session()->getId(),
            'session_owner_id' => $user?->id,
        ]);
    }
}