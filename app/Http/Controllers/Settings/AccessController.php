<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

use App\Models\Rolepermission;
use App\Models\Permission;
use App\Models\Role;

class AccessController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('module', '!=', 'permission')->orderBy('module','asc')->get();
        $modules = Permission::where('module', '!=', 'permission')->distinct()->get(['module']);

        $roles = Role::where('id', '!=', 1)->orderBy('id', 'asc')->get();

        $access = Rolepermission::where('isAllowed', 1)->get();

        return view('admin.settings.role_permission.index', compact('permissions','roles','access','modules'));
    }

    public function update_roles_and_permissions(Request $request)
    {
        $rolesPermissions = Rolepermission::all();

        if ($request->cb) {
            foreach ($request->cb as $key => $val) {
                $keys = explode("_", $key);
                $permissionId = filter_var($keys[0], FILTER_SANITIZE_NUMBER_INT);
                $roleId = filter_var($keys[1], FILTER_SANITIZE_NUMBER_INT);

                Rolepermission::updateOrCreate(
                    [
                        'permission_id' => $permissionId,
                        'role_id' => $roleId
                    ],
                    [
                        'permission_id' => $permissionId,
                        'role_id' => $roleId,
                        'user_id' => auth()->id(),
                        'isAllowed' => 1
                    ]
                );

                $rolesPermissions = $rolesPermissions->reject(function ($rolePermission) use($permissionId, $roleId) {
                    return $rolePermission->permission_id == $permissionId && $rolePermission->role_id == $roleId;
                });
            }
        }

        foreach ($rolesPermissions as $rolePermission) {
            $rolePermission->update(['isAllowed' => 0]);
        }

        return back()->with('success', __('standard.account_management.access_rights.update_success'));
    }
}
