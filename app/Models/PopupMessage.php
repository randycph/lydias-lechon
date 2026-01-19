<?php

namespace App\Models;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PopupMessage extends Model
{
    use SoftDeletes;
    use LogsActivityDiff;

    protected $fillable = [
        'title',
        'message',
        'button_text',
        'close_button_text',
        'is_active',
        'url',
        'image',
        'start_to_show',
        'button_text_url',
        'user_id'
    ];

    public function getButtonTextAttribute($value)
    {
        return $value ?: 'OK';
    }

    public function getCloseButtonTextAttribute($value)
    {
        return $value ?: 'Close';
    }
}
