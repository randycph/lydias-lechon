<?php

namespace App\Models;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use SoftDeletes;
    use LogsActivityDiff;
    use HasFactory;

    protected $table = 'settings';
    protected $fillable = [
        'api_key',
        'website_name',
        'website_favicon',
        'company_logo',
        'company_favicon',
        'company_name',
        'disable_delivery_misc',
        'disable_delivery_misc_dates',
        'google_analytics',
        'google_recaptcha_sitekey',
        'google_recaptcha_secret',
        'data_privacy_title',
        'data_privacy_popup_content',
        'data_privacy_content',
        'mobile_no',
        'fax_no',
        'tel_no',
        'email',
        'company_about',
        'company_address',
        'google_map',
        'social_media_accounts',
        'copyright',
        'user_id',
        'is_rating_need_approval',
        'is_rating_allow_anonymous',
        'is_display_review_date',
        'minimum_order',
        'disable_order',
        'order_message',
        'disable_delivery',
        'minimum_processing_hours',
        'minimum_processing_hours_misc',
        'announcement',
        'cutoff',
        'kiosk_express_categories',
        'minimum_order_pickup',
        'disable_pickup_dates',
        'disable_delivery_dates',
        'minimum_order_misc',
        'minimum_processing_hours_baka'
    ];

    public static function getWebsiteName()
    {
        $data = Setting::where('id', 1)->first();

        return $data->website_name;
    }

    public static function getCopyright()
    {
        $data = Setting::where('id', 1)->first();

        return $data->copyright;
    }
}
