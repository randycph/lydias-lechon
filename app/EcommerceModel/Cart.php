<?php

namespace App\EcommerceModel;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    protected $table = 'ecommerce_shopping_cart';
    protected $fillable = ['user_id', 'product_id', 'price', 'qty','paella_price','coupon_code','coupon_amount','parent_id','is_misc'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo('\App\Product');
    }

    public function getItemTotalPriceAttribute()
    {
        return ($this->product->price * $this->qty) + ($this->paella_price * $this->qty);
    }

    public function str_total_price()
    {
        return ucfirst(strtolower($this->product->currency))." ".number_format($this->itemTotalPrice,2);
    }

    public function getItemTotalInstallationFeeAttribute()
    {
        return 0;
    }

    public function str_total_installtion_fee()
    {
        return 0;
    }

    public function getGrandPriceAttribute()
    {
        return $this->itemTotalPrice;
    }

    public function str_grand_price()
    {
        return ucfirst(strtolower($this->product->currency))." ".number_format($this->grand_price,2);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function products_total_amount()
    {
        return $this->product->pluck('price')->get();
    }

}
