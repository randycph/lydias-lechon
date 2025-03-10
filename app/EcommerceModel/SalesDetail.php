<?php

namespace App\EcommerceModel;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDetail extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_sales_details';
    protected $fillable = ['sales_header_id', 'product_id', 'product_name', 'product_category', 'price', 'cost', 'tax_amount', 'promo_id', 'promo_description', 'discount_amount', 'gross_amount', 'net_amount', 'qty', 'uom', 'created_by','other_cost','other_cost_description','size','no_of_pax','paella_price', 'delivery_date','production_status', 'cancellation_reason', 'paella_qty'
];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function joborders()
    {
        return $this->hasMany(JobOrder::class,'sales_detail_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function header()
    {
        return $this->belongsTo(SalesHeader::class, 'sales_header_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class,'product_category');
    }

}
