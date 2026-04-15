<?php

namespace App\Models;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;

class MediaAccounts extends Model
{
    use LogsActivityDiff;
    
    public $table = 'social_media';

    protected $fillable = [ 'name', 'media_account', 'user_id',];

    public $timestamps = false;
}
