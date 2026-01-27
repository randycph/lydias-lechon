<?php

namespace App\Models;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model
{
    use SoftDeletes;
    use LogsActivityDiff;
    
    public $table = 'service_types';
    protected $fillable = ['name', 'status', 'created_by',];

}
