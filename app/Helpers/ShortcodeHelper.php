<?php

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

if (!function_exists('parse_shortcodes')) {
    function parse_shortcodes($content)
    {
        $content = stripslashes($content);
        
        return preg_replace_callback('/\[shortcodes_products\s+(.*?)\]/', function ($matches) {
            preg_match_all('/(\w+)=(".*?"|\'.*?\'|\S+)/', $matches[1], $attrMatches);

            $attributes = [];
            foreach ($attrMatches[1] as $i => $key) {
                $value = $attrMatches[2][$i];
                $value = trim($value, '"\'');
                $attributes[$key] = $value;
            }

            $show = isset($attributes['show']) ? (int) $attributes['show'] : 5;
            $category = isset($attributes['category']) ? (int) $attributes['category'] : null;

            $products = Product::with([
                        'photos',
                        'addonProducts' => function ($q) {
                            $q->with(['photos']);
                        }
                    ])
                    ->where('category_id', $category)
                    ->where('status', 'PUBLISHED')->take($show)->get();

            foreach ($products as $product) {
                if ($product->addonProducts->isEmpty()) {
                    $addonProductIds = DB::table('ecommerce_sales_details')
                        ->select('product_id', DB::raw('COUNT(id) as total'))
                        ->whereIn('sales_header_id', function ($query) use ($product) {
                            $query->select('sales_header_id')
                                    ->from('ecommerce_sales_details')
                                    ->where('product_id', $product->id);
                        })
                        ->where('product_id', '!=', $product->id)
                        ->groupBy('product_id')
                        ->orderByDesc('total')
                        ->limit(5)
                        ->pluck('product_id');

                    $fallbackAddons = Product::whereIn('id', $addonProductIds)
                        ->where('status', 'PUBLISHED')
                        ->with(['photos'])
                        ->get();

                    $product->setRelation('addonProducts', $fallbackAddons);
                }
            }

            return View::make('components.shortcode-products', compact('products'))->render();
        }, $content);
    }
}
