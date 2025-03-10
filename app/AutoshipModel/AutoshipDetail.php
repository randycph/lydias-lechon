<?php

namespace App\AutoshipModel;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class AutoshipDetail extends Model
{

    protected $table = 'ecommerce_autoship_detail';
    protected $fillable = ['autoship_header_id', 'product_id', 'price', 'qty', 'uom'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function autoship()
    {
    	return $this->hasMany(Autoship::class,'autoship_header_id');
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
