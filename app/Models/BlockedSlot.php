<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedSlot extends Model
{
    protected $fillable = [
        'scope',
        'category_id',
        'product_id',
        'date',
        'start_time',
        'end_time',
        'is_all_day',
        'block_type',
    ];
}
