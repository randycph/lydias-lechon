<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leftovers extends Model
{
	use SoftDeletes;
    protected $fillable = ['qty', 'product_id', 'remarks', 'user_id','uom', 'date', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo('App\EcommerceModel\Branch');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
