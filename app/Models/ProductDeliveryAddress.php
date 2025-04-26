<?php

namespace App\Models;

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
}
