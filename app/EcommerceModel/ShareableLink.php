<?php

namespace App\EcommerceModel;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareableLink extends Model
{
    use SoftDeletes;
    use LogsActivityDiff;

    protected $table = 'media_shareable_links';
    protected $fillable = ['name', 'soc_media', 'url', 'user_id'];
    protected $timestamp = true;


}
