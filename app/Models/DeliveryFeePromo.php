<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class DeliveryFeePromo extends Model
{
    

    protected $table = 'delivery_fee_promo';
    protected $fillable = ['ref_id', 'type', 'user_id'];

    public function getRefAttribute()
    {    	
        return $this->belongsTo(User::class);
    }

    public static function check_customer($customer)
    {    	
    	$record = DeliveryFeePromo::where('type','customer')->where('ref_id',$customer)->first();

    	if(!$record)
    		return 0;    	
    	else
    		return 1;        
    }

    public static function check_product($product)
    {    	
    	$record = DeliveryFeePromo::where('type','product')->where('ref_id',$product)->first();

    	if(!$record)
    		return 0;    	
    	else
    		return 1;        
    }

   
}
