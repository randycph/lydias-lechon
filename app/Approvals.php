<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Approvals extends Model
{
    

    protected $table = 'approvals';
    protected $fillable = ['approval_code', 'user_id', 'approval_type', 'reference_id', 'remarks'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

   
}
