<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Role;

class Permission extends Model
{
    use SoftDeletes;

    public $table = 'permission';

    protected $fillable = [ 'name', 'module', 'description', 'routes', 'methods', 'user_id', 'is_view_page'];

    protected $casts = [
        'routes' => 'array',
        'methods' => 'array',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission')->where('isAllowed', 1);
    }

    public function module_code()
    {
        return implode("_", explode(' ', $this->module));
    }

    public static function module_init($controller, $moduleName)
    {
        $permissions = Permission::where('module', $moduleName)->get();

        foreach ($permissions as $permission) {
            //logger($permission);
            $controller->middleware('checkAccessRights:'.$permission->id, ['only' => $permission->methods]);
        }
    }

    public static function has_access_to_route($routeId)
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
    
    public static function modules()
    {
        return [
            'customer' => 'Customers',
            'job_order' => 'Job Orders',
            'forecaster' => 'Forecaster',
            'left_over' => 'Left Over',
            'reports' => 'Reports',
            'page' => 'Pages',
            'banner' => 'Banners',
            'file_manager' => 'File Manager',
            'menu' => 'Menu',
            'news' => 'News',
            'news_category' => 'News Category',
            'website_settings' => 'Website Settings',
            'audit_logs' => 'Audit Trail',
            'user' => 'Users',
            'subscriber' => 'Subscribers',
            'subscriber_group' => 'Subscriber Group',
            'campaign' => 'Campaigns',
            'sent_item' => 'Sent Items',
            'product' => 'Product',
            'product_category' => 'Product Category',
            'production_branch' => 'Production Branch',
            'gift_certificate' => 'Gift Certificate',
            'delivery_rate' => 'Delivery Rate',
            'branch' => 'Branch',
            'sales_transaction' => 'Sales Transaction',
            'shareable_link' => 'Shareable Link'
        ];
    }
    
    // public static function modules()
    // {
    //     return [
    //         'customer' => 'Customer',
    //         'job_order' => 'Job Order',
    //         'forecaster' => 'Forecaster',
    //         'left_over' => 'Left Over',
    //         'reports' => 'Reports',
    //         'page' => 'Page',
    //         'banner' => 'Banner',
    //         'file_manager' => 'File Manager',
    //         'menu' => 'Menu',
    //         'news' => 'News',
    //         'news_category' => 'News Category',
    //         'website_settings' => 'Website Settings',
    //         'audit_logs' => 'Audit Logs',
    //         'user' => 'User',
    //         'subscriber_group' => 'Subscriber Group',
    //         'subscriber' => 'Subscriber',
    //         'campaign' => 'Campaign',
    //         'sent_item' => 'Sent Campaign',
    //         'product' => 'Product',
    //         'product_category' => 'Product Category',
    //         'production_branch' => 'Production Branch',
    //         'gift_certificate' => 'Gift Certificate',
    //         'delivery_rate' => 'Delivery Rate',
    //         'branch' => 'Branch',
    //         'sales_transaction' => 'Sales Transaction',
    //     ];
    // }
}
