<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchNumbers extends Model
{
    public $table = 'branch_numbers';
    protected $fillable = [ 'branch_id', 'number', 'name' ];

    public function branch()
    {
        return $this->belongsTo('App\EcommerceModel\Branch');
    }
}
