<?php

namespace App\EcommerceModel;

use App\Helpers\Webfocus\Setting;
use App\Mail\Member\EnrollMemberMail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class Member extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'members';
    protected $fillable = ['user_id', 'sponsor_id', 'code', 'entity_type', 'government_id_type', 'government_id',
        'government_id_photo', 'first_name', 'middle_name', 'last_name', 'birthday', 'email', 'mobile', 'phone',
        'work_phone', 'fax', 'address_street', 'address_city', 'address_province', 'address_zip', 'address_zip',
        'address_country', 'address_delivery_street', 'address_delivery_city', 'address_delivery_province',
        'address_delivery_zip', 'address_delivery_country', 'security_question', 'security_answer', 'status', 'class'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function childs() {
        return $this->hasMany('App\EcommerceModel\Member','sponsor_id','user_id') ;
    }

    public function send_enrolled_by_member_email()
    {
        $token = app('auth.password.broker')->createToken($this->user);

        Mail::to($this->email)->send(new EnrollMemberMail(Setting::info(), $this->user, $this->sponsor->user, $token));
    }

    public function sponsor()
    {
        return $this->belongsTo('App\EcommerceModel\Member', 'sponsor_id', 'id');
    }

    public function complete_address()
    {
        return "{$this->address_street}, {$this->address_city}, {$this->address_province} ({$this->address_zip}), {$this->address_country}";
    }

    public function complete_delivery_address()
    {
        return "{$this->address_delivery_street}, {$this->address_delivery_city}, {$this->address_delivery_province} ({$this->address_delivery_zip}), {$this->address_delivery_country}";
    }

    public function has_sponsor()
    {
        return !empty($this->sponsor_id);
    }

    public function getDeliveryAddressAttribute()
    {
        return "{$this->address_delivery_street}, {$this->address_delivery_city} {$this->address_delivery_province} {$this->address_delivery_zip},  {$this->address_delivery_country}";
    }

    public function getMemberAddressAttribute()
    {
        return "{$this->address_street}, {$this->address_city} {$this->address_province} {$this->address_zip}, {$this->address_country}";
    }


    // public function getNameAttribute()
    // {
    //     return "$this->first_name $this->last_name";
    // }

    public function getReferralLinkAttribute()
    {
        return route('member-front.sign-up')."?member=".$this->code;
    }

    public static function ranks()
    {
        return ['Warrior', 'Ranger', 'Elite Ranger', 'Master', 'Grand Master', 'Legend', 'Grand Legend'];
    }

    public static function entity_types()
    {
        return ['Individual', 'Partnership', 'Corporation'];
    }

    public static function government_id_types()
    {
        return ['TIN', 'SSS'];
    }

    public static function security_questions()
    {
        return ['What was the house number and street name you lived in as a child?',
            'What were the last four digits of your childhood telephone number?',
            'What primary school did you attend?',
            'In what town or city was your first full time job?',
            'In what town or city did you meet your spouse/partner?',
            'What is the middle name of your oldest child?',
            'What are the last five digits of your driver\'s licence number?',
            'What is your grandmother\'s (on your mother\'s side) maiden name?',
            'What is your spouse or partner\'s mother\'s maiden name?'];
    }
}
