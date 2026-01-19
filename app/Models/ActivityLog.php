<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'cms_activity_logs';
    protected $fillable = ['created_by', 'activity_type', 'dashboard_activity', 'activity_desc', 'activity_date',
        'db_table', 'old_value', 'new_value', 'reference', 'subject_type', 'subject_id', 'ip_address'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
