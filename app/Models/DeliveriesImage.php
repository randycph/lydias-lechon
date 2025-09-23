<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveriesImage extends Model
{
    protected $fillable = ['image', 'delivery_status_id', 'user_id'];
}
