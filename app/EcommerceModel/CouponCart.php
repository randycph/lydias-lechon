<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use App\EcommerceModel\Coupon;
use App\Models\Concerns\LogsActivityDiff;
use App\Models\User;

class CouponCart extends Model
{
    use LogsActivityDiff;
    
	protected $table = 'coupon_cart';
    protected $fillable = [ 'coupon_id','product_id','customer_id','total_usage', 'status', 'sales_header_id', 'coupon_code', 'discount_used' ];
    public $timestamps = true;

    public function details()
    {
    	return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function product_details()
    {
        return $this->belongsTo('\App\EcommerceModel\Product','product_id');
    }

    public static function coupon_exist($id)
    {
    	$count = CouponCart::where('coupon_id',$id)->count();

    	return $count;
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function sales_header()
    {
        return $this->belongsTo(SalesHeader::class, 'sales_header_id');
    }

    public function getCouponCodeAttribute()
    {
        return $this->coupon ? $this->coupon->coupon_code : null;
    }
}
