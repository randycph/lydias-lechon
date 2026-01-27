<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'cms_activity_logs';
    protected $fillable = ['created_by', 'activity_type', 'dashboard_activity', 'activity_desc', 'activity_date',
        'db_table', 'old_value', 'new_value', 'reference'];
    public $timestamps = false;

    
    
    public function getDbTableAttribute($value)
    {
        if ($value == 'albums') {
            $value = 'Albums';
        } elseif ($value == 'articles') {
            $value = 'Articles';
        } else if ($value == 'banners') {
            $value = 'Banners';
        } else if ($value == 'branches') {
            $value = 'Branches';
        } else if ($value == 'cms_activity_logs') {
            $value = 'Activity Logs';
        } else if ($value == 'campaigns') {
            $value = 'Campaigns';
        } else if ($value == 'coupons') {
            $value = 'Coupons';
        } else if ($value == 'deliverable_cities') {
            $value = 'Rates';
        } else if ($value == 'pages') {
            $value = 'Pages';
        } else if ($value == 'products') {
            $value = 'Products';
        } else if ($value == 'settings') {
            $value = 'Settings';
        } else if ($value == 'users') {
            $value = 'Users';
        } elseif ($value == 'ecommerce_sales_details') {
            $value = 'Sales Details';
        } elseif ($value == 'ecommerce_sales_headers') {
            $value = 'Sales Transactions';
        } elseif ($value == 'ecommerce_sales_payments') {
            $value = 'Sales Payments';
        } elseif ($value == 'gift_certificate') {
            $value = 'Gift Certificates';
        } elseif ($value == 'groups') {
            $value = 'Groups';
        } elseif ($value == 'leftovers') {
            $value = 'Leftovers';
        } elseif ($value == 'menus') {
            $value = 'Menus';
        } elseif ($value == 'permission') {
            $value = 'Permission';
        } elseif ($value == 'popup_messages') {
            $value = 'Popup Messages';
        } elseif ($value == 'production_branches') {
            $value = 'Production Branches';
        } elseif ($value == 'production_orders') {
            $value = 'Production Orders';
        } elseif ($value == 'product_tags') {
            $value = 'Product Tags';
        } elseif ($value == 'social_media') {
            $value = 'Social Media';
        } elseif ($value == 'product_categories') {
            $value = 'Product Categories';
        } elseif ($value == 'subscribers') {
            $value = 'Subscribers';
        } else {
            $value = ucwords(str_replace('_', ' ', $value));
        }

        return $value;
    }
}
