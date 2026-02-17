<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Approvals extends Model
{
    protected $table = 'approvals';
    protected $fillable = [
        'approval_code', 
        'user_id', 
        'approval_type', 
        'reference_id', 
        'remarks', 
        'payment_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
