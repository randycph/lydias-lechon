<?php


namespace App\Shortcodes;


use App\Product;

class ProductsShortcodes
{

    public function get_featured_products($shortcode, $content, $compiler, $name, $viewData)
    {
        $featuredProducts = Product::where('is_featured', 1)->whereStatus('Published')->get();

        if ($featuredProducts->count() == 0) {
            return '';
        }

        return sprintf(view('theme.'.env('FRONTEND_TEMPLATE').'.pages.shortcodes.home-products', compact('featuredProducts')), $shortcode->class, $content);
    }
}
