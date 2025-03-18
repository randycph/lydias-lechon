<?php

namespace App\Models;

use App\EcommerceModel\Member;
use App\Notifications\NewUserResetPasswordNotification;
use App\Notifications\UserResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;


use App\Models\Role;

use Cookie;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password', 'role_id', 'is_active', 'remember_token', 'username', 'user_type', 'firstname', 'lastname', 'avatar', 'user_id', 'address_street', 'address_municipality', 'address_city', 'address_region', 'registration_source', 'contact_tel', 'contact_mobile', 'contact_fax', 'contact_person', 'is_org', 'organization', 'agent_code', 'birthday', 'security_questions', 'security_answer', 'branch', 'isDeleted','is_subscribe','allowed_payments'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_role()
    {
        return $this->belongsTo('\App\Role', 'role_id');
    }

    public function branches()
    {
        return $this->hasMany('App\EcommerceModel\UserBranch');
    }



    public function getFullNameAttribute()
    {
        if($this->is_org == 1)
            return "$this->organization";
        else
            return "$this->firstname $this->lastname";
    }

    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getRoleAttribute($value)
    {
        return strtoupper($value);
    }

    public function complete_address()
    {
        return "{$this->address_street} {$this->address_municipality}, {$this->address_city}, {$this->address_region}";
    }

    public function role_name()
    {
        return User::userRole($this->role_id);
    }

    public static function totalUser()
    {
        $total = User::where('is_active','=',1)->where('user_type', 'cms')->count();

        return $total;
    }

    public static function activeTotalUser()
    {
        $total = User::where('is_active','=',1)->where('user_type', 'cms')->count();

        return $total;
    }

    public static function inactiveTotalUser()
    {
        $total = User::where('is_active','=',0)->where('user_type', 'cms')->count();

        return $total;
    }

    public static function userEmail($id)
    {
        $data = User::where('id',$id)->first();

        return $data->email;
    }

    public static function userRole($id)
    {
        $data = Role::where('id',$id)->first();

        if (!$data) {
            return '';
        }

        return $data->name;
    }

    public function send_reset_password_email()
    {
        $token = app('auth.password.broker')->createToken($this);

        $this->notify(new UserResetPasswordNotification($token));
    }

    public function send_reset_temporary_password_email()
    {
        $token = app('auth.password.broker')->createToken($this);

        $this->notify(new NewUserResetPasswordNotification($token));
    }

    public function has_access_to_route($route)
    {
        
        if ($this->is_an_admin()) {
            return true;
        }

        $userPermissionRoutes = $this->get_assigned_routes();

        if (in_array($route, $userPermissionRoutes)) {
            return true;
        }

        return false;
    }

    public function get_assigned_routes()
    {
        $permission = $this->assign_role->permissions;

        if ($permission) {
            return $permission->pluck('routes')->flatten()->all();
        }

        return [];
    }

    public function has_access_to_albums_module()
    {
        return $this->has_access_to_module('banner');
    }

    public function has_access_to_audit_logs_module()
    {
        return $this->has_access_to_module('audit_logs');
    }

    public function has_access_to_news_categories_module()
    {
        return $this->has_access_to_module('news_category');
    }

    public function has_access_to_cms_settings_module()
    {
        return $this->has_access_to_module('cms settings');
    }

    public function has_access_to_file_manager_module()
    {
        return $this->has_access_to_module('file_manager');
    }

    public function has_access_to_menu_module()
    {
        return $this->has_access_to_module('menu');
    }

    public function has_access_to_news_module()
    {
        return $this->has_access_to_module('news');
    }

    public function has_access_to_pages_module()
    {
        return $this->has_access_to_module('page');
    }

    public function has_access_to_permissions_module()
    {
        return $this->has_access_to_module('permission');
    }

    public function has_access_to_roles_module()
    {
        return $this->has_access_to_module('role');
    }

    public function has_access_to_user_module()
    {
        return $this->has_access_to_module('user');
    }

    public function has_access_to_website_settings_module()
    {
        return $this->has_access_to_module('website_settings');
    }

    public function has_access_to_video_module()
    {
        return $this->has_access_to_module('videos');
    }

    public function has_access_to_video_download_request_module()
    {
        return $this->has_access_to_module('video download request');
    }

    public function has_access_to_video_category_module()
    {
        return $this->has_access_to_module('video category');
    }

    public function assign_role()
    {
        return $this->belongsTo(ModelsRole::class,'role_id', 'id');
    }

    public function has_access_to_module($module)
    {
        if ($this->is_an_admin()) {
            return true;
        }

        $routes = $this->get_module_routes($module);

        foreach($routes as $route) {
            if ($this->is_route_exist_to_user_permission($route)) {
                return true;
                break;
            }
        }

        return false;
    }

    private function get_module_routes($module)
    {
        return Permission::where('module', $module)->pluck('name');
    }

    private function is_route_exist_to_user_permission($route)
    {
        return \App\ViewPermissions::check_permission($this->role_id, $route) == 1;
    }

    public function get_image_url_storage_path()
    {
        $delimiter = 'storage/';
        if (strpos($this->avatar, $delimiter) !== false) {
            $paths = explode($delimiter, $this->avatar);
            return $paths[1];
        }

        return '';
    }

    public function get_image_file_name()
    {
        $path = explode('/', $this->avatar);
        $nameIndex = count($path) - 1;
        if ($nameIndex < 0)
            return '';

        return $path[$nameIndex];
    }

    public function is_an_admin()
    {
        return $this->role_id == 1;
    }

    // public function is_a_cms_user() {
    //     return !empty($this->role_id) && $this->user_type == 'cms';
    // }

    // public function is_a_member_user() {
    //     return $this->user_type == 'member';
    // }

    // public function is_a_incomplete_member_user() {
    //     return $this->user_type == 'incomplete_member';
    // }


    // // public function is_a_cms_user() {
    // //     return $this->user_type == 'cms';
    // // }

    // public function is_a_customer_user() {
    //     return $this->user_type == 'customer';
    // }

    // public function is_a_branch_user() {
    //     return $this->user_type == 'branch';
    // }

    // public function is_a_staff_user() {
    //     return $this->user_type == 'staff';
    // }

    // public function is_a_forecaster_user() {
    //     return $this->user_type == 'forecaster';
    // }

    // public function is_a_dispatcher_user() {
    //     return $this->user_type == 'user';
    // }

    public function is_a_cms_user() {
        return $this->user_type == 'cms' && $this->role_id == 1;
    }

    public function is_a_member_user() {
        return $this->user_type == 'member';
    }

    public function is_a_incomplete_member_user() {
        return $this->user_type == 'incomplete_member';
    }


    // public function is_a_cms_user() {
    //     return $this->user_type == 'cms';
    // }

    public function is_a_customer_user() {
        return $this->user_type == 'customer';
    }

    public function is_a_branch_user() {
        return $this->user_type == 'cms' && $this->role_id == 2;
    }

    public function is_a_staff_user() {
        return $this->user_type == 'cms' && $this->role_id == 4;
    }

    public function is_a_forecaster_user() {
        return $this->user_type == 'cms' && $this->role_id == 3;
    }

    public function is_a_dispatcher_user() {
        return $this->user_type == 'cms' && $this->role_id == 5;
    }

    public function profile()
    {
        return $this->hasOne(Member::class, 'user_id');
    }


    public static function customer_lookup()
    {
       $customers = User::where('user_type','customer')->get();
       $names = array();

        foreach($customers as $customer){
            $names[] = $customer->name;
        }

        return json_encode($names);
    }
    
    public static function previous_customer_lookup()
    {
       $customers = \App\EcommerceModel\SalesHeader::distinct()->get(['customer_name']);
       $names = array();

        foreach($customers as $customer){
            $names[] = $customer->customer_name;
        }

        return json_encode($names);
    }

    public function getEmailAttribute($value)
    {
        $str = substr($value,0,7);
        if($str == 'lydtmp_')
            return '';
        else
            return ucfirst($value);
    }
    
    public static function order_origin($origin){
        Cookie::queue('origin', $origin, 60);
    }

}
