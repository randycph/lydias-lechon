<?php

namespace App\AutoshipModel;

use Illuminate\Database\Eloquent\Model;

class AutoshipDetail extends Model
{

    protected $table = 'ecommerce_autoship_detail';
    protected $fillable = ['autoship_header_id', 'product_id', 'price', 'qty', 'uom'];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function autoship()
    {
    	return $this->hasMany('App\AutoshipModel\Autoship','autoship_header_id');
    }

    public function getTotalPriceAttribute()
    {
        return ($this->product->price * $this->qty);
    }

    public function getItemTotalPriceAttribute()
    {
        return ($this->product->price * $this->qty);
    }

}
