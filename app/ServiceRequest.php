<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    public $table = 'service_request';
    protected $fillable = [ 'service_type', 'serial_no', 'date', 'preferred_date1', 'preferred_date2', 'status', 'description', 'created_by', 'scheduled_date', 'message_id'];

    public function service_name()
    {
        return $this->belongsTo('App\ServiceType', 'service_type');
    }

    public function requestor()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
