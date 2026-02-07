<?php

namespace App\Models;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSize extends Model
{
    use SoftDeletes;
    use LogsActivityDiff;

    protected $fillable = [
        'product_id',
        'name',
        'description',
        'added_by',
        'updated_by',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
