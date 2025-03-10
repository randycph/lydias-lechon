<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model
{
    use SoftDeletes;
    
    public $table = 'service_types';
    protected $fillable = ['name', 'status', 'created_by',];

}
