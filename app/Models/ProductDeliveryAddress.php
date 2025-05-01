<?php

namespace App\Models;

use App\EcommerceModel\SalesHeader;
use Illuminate\Database\Eloquent\Model;

class ProductDeliveryAddress extends Model
{
    protected $table = 'product_delivery_addresses';

    protected $fillable = [
        'sales_header_id',
        'user_id',
        'product_id',
        'sale_id',
        'address',
        'contact_person',
        'contact_tel',
        'order',
        'qty',
        'delivery_date',
        'delivery_time',
        'delivery_status',
        'delivery_fee',
        'location',
        'branch'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function salesHeader()
    {
        return $this->belongsTo(SalesHeader::class, 'sales_header_id');
    }
}
