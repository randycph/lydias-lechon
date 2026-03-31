<?php

namespace App\Models;

use App\EcommerceModel\Branch;
use Illuminate\Database\Eloquent\Model;

class BlockedSlot extends Model
{
    protected $fillable = [
        'scope',
        'date',
        'start_time',
        'end_time',
        'is_all_day',
        'block_type',
        'group_id',
        'date_mode',
    ];

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'blocked_slot_products',
            'blocked_slot_id',
            'product_id'
        );
    }

    public function categories()
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'blocked_slot_categories',
            'blocked_slot_id', 
            'category_id'
        );
    }

    public function comboProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'blocked_slot_combo_products',
            'blocked_slot_id',
            'product_id'
        );
    }

    public function comboCategories()
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'blocked_slot_combo_categories',
            'blocked_slot_id',
            'category_id'
        );
    }

    public function locations()
    {
        return $this->belongsToMany(
            Branch::class,
            'blocked_slot_locations',
            'blocked_slot_id',
            'location_id'
        );
    }
}
