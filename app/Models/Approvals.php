<?php

namespace App\Models;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Approvals extends Model
{
    use LogsActivityDiff;

    protected $table = 'approvals';
    protected $fillable = ['approval_code', 'user_id', 'approval_type', 'reference_id', 'remarks'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}
