<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table = 'role';

    protected $fillable = [ 'name', 'description', 'created_by',];

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
                ->where('isAllowed', 1)->where('module', $module);
        }

        return $this->belongsToMany(Permission::class, 'role_permission')
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
