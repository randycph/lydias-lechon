<?php

namespace App\Models;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;

class Rolepermission extends Model
{
    use LogsActivityDiff;
    
    public $table = 'role_permission';

    protected $fillable = [ 'role_id', 'permission_id', 'user_id', 'isAllowed' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission');
    }
}
