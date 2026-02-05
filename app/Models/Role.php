<?php

namespace App\Models;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use LogsActivityDiff;
    
    public $table = 'role';

    protected $fillable = [ 'name', 'description', 'created_by', 'can_approve_payment', 'has_branches', 'has_production_branch'];

    public function is_admin() {
        return $this->id == 1;
    }

    public function is_not_admin() {
        return $this->id != 1;
    }

    public function is_not_customer() {
        return $this->id != 6;
    }

    public function permissions($module = null)
    {
        if ($module) {
            return $this->belongsToMany(Permission::class, 'role_permission')
                ->withPivot(['user_id', 'isAllowed'])
                ->where('isAllowed', 1)->where('module', $module);
        }

        return $this->belongsToMany(Permission::class, 'role_permission')
            ->withPivot(['user_id', 'isAllowed'])
            ->where('isAllowed', 1);
    }

    public function all_permissions($module = null)
    {
        if ($module) {
            return $this->belongsToMany(Permission::class, 'role_permission')
                ->where('module', $module);
        }

        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public static function has_permission_to_route($routeId)
    {
        if (auth()->check())
        {
            $userPermissions = auth()->user()->assign_role->permissions;
            if ($userPermissions->count())
            {
                $permissionIds = $userPermissions->pluck('id')->toArray();

                return (in_array($routeId, $permissionIds));
            }
        }

        return false;
    }
}
