<?php

namespace App\EcommerceModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DeliveryStatus extends Model
{

    protected $table = 'ecommerce_delivery_status';
    protected $fillable = ['order_id', 'user_id', 'status', 'remarks','delivered_by'];


    public function getDeliveryAddressAttribute()
    {
        return "{$this->address_delivery_street} {$this->address_delivery_brgy}, {$this->address_delivery_city} {$this->address_delivery_province} {$this->address_delivery_zip}";
    }

    public function sales()
	{
	    return $this->belongsTo('App\EcommerceModel\SalesHeader','order_id');
	}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
