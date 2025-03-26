<?php


namespace App\Shortcodes;


use App\Models\Product;

class ProductsShortcodes
{

    public function get_featured_products($shortcode, $content, $compiler, $name, $viewData)
    {
        $featuredProducts = Product::where('is_featured', 1)->whereStatus('Published')->get();

        if ($featuredProducts->count() == 0) {
            return '';
        }

        return sprintf(view('theme.'.config('app.frontend_template').'.pages.shortcodes.home-products', compact('featuredProducts')), $shortcode->class, $content);
    }
}
