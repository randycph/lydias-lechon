<?php

namespace App\Models;

use App\EcommerceModel\Branch;
use Illuminate\Database\Eloquent\Model;

class BranchNumbers extends Model
{
    public $table = 'branch_numbers';
    protected $fillable = [ 'branch_id', 'number', 'name', 'type' ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
