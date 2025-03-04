<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareableLink extends Model
{
    use SoftDeletes;

    protected $table = 'media_shareable_links';
    protected $fillable = ['name', 'soc_media', 'url', 'user_id'];
    protected $timestamp = true;


}
