<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class FeaturedProductsShortcode extends Controller
{
    public function render($attributes = [])
    {
        $featuredProducts = Product::where('is_featured', 1)->whereStatus('Published')->get();

        if ($featuredProducts->count() == 0) {
            return '';
        }

        return view('shortcodes.featured_products', compact('featuredProducts'))->render();
    }
}
