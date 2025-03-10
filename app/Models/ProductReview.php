<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductReview extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_product_review';
    protected $fillable = ['user_id', 'review', 'rating', 'parent_id', 'product_id', 'status'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getRatingStarAttribute(){
        $star = 5 - (integer) $this->rating;
        $front = '';
        for($x = 1; $x<=$this->rating; $x++){
            $front.='<span class="fa fa-star checked"></span>';
        }

        for($x = 1; $x<=$star; $x++){
            $front.='<span class="fa fa-star"></span>';
        }

        return $front;
    }



}
