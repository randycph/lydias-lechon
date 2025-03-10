<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    public $table = 'products_variations';
    protected $fillable = [ 'product_id', 'color', 'size' ];

    public static function colors($id)
    {
        $datas = ProductVariation::select('color')->distinct('color')->where('product_id',$id)->get();

        $colors = "";

        foreach($datas as $data)
        {
            $colors .= $data->color.',';
        }

        return rtrim($colors,",");
    }

    public static function sizes($id)
    {
        $datas = ProductVariation::select('size')->distinct('size')->where('product_id',$id)->get();

        $sizes = "";

        foreach($datas as $data)
        {
            $sizes .= $data->size.',';
        }
        
        return rtrim($sizes,",");
    }
}
