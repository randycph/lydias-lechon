<?php

namespace App\EcommerceModel;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_promos';
    protected $fillable = ['name', 'description', 'amount', 'percentage', 'effectivity_start', 'effectivity_end', 'min_subtotal', 'max_subtotal', 'usage_limit', 'apply_for', 'coupon_code', 'status', 'conditions', 'created_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getApplyForAttribute($value)
    {
        return json_decode($value, true);
    }

    public function geCouponCodeAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setApplyForAttribute($arrayValue)
    {
        $newValue = [];
        foreach ($arrayValue as $value) {
            if (!empty($value)) {
                array_push($newValue, $value);
            }
        }

        $this->attributes['apply_for'] = json_encode($newValue);
    }

    public function setCouponCodeAttribute($arrayValue)
    {
        $newValue = [];
        foreach ($arrayValue as $value) {
            if (!empty($value)) {
                array_push($newValue, $value);
            }
        }

        $this->attributes['coupon_code'] = json_encode($newValue);
    }

}
