<?php

use App\EcommerceModel\Branch;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

if (!function_exists('vite_asset')) {
    function vite_asset($path)
    {
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $key = $path;

        if (!isset($manifest[$key])) {
            throw new Exception("Vite asset not found: {$key}");
        }

        return asset('build/' . $manifest[$key]['file']);
    }
}

if (!function_exists('parse_shortcodes')) {
    function parse_shortcodes($content)
    {
        $content = stripslashes($content);

        return preg_replace_callback('/\[(shortcodes_\w+)(\s.*?)?\]/', function ($matches) {
            $tag = $matches[1]; // e.g., shortcodes_products or shortcodes_menu
            $rawAttributes = $matches[2] ?? '';

            // Parse attributes
            preg_match_all('/(\w+)=(".*?"|\'.*?\'|\S+)/', $rawAttributes, $attrMatches);
            $attributes = [];
            foreach ($attrMatches[1] as $i => $key) {
                $value = trim($attrMatches[2][$i], "\"'");
                $attributes[$key] = $value;
            }

            // Dispatch to appropriate handler
            return handle_shortcode($tag, $attributes);
        }, $content);
    }
}

if (!function_exists('handle_shortcode')) {
    function handle_shortcode($tag, $attributes = [])
    {
        switch ($tag) {
            case 'shortcodes_products':
                $show = isset($attributes['show']) ? (int) $attributes['show'] : 100;
                $category = isset($attributes['category']) ? (int) $attributes['category'] : 1;

                $products = Product::with([
                        'photos',
                        'addonProducts' => function ($q) {
                            $q->with(['photos']);
                        }
                    ])
                    ->where('category_id', $category)
                    ->where('status', 'PUBLISHED')
                    ->take($show)
                    ->get();

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
                            ->where('sold_out', 0)
                            ->where('status', 'PUBLISHED')
                            ->with(['photos'])
                            ->get();

                        $product->setRelation('addonProducts', $fallbackAddons);
                    }
                }

                return View::make('components.shortcode-products', compact('products'))->render();

            case 'shortcodes_menu':
                $categories = ProductCategory::with(['products' => function ($query) {
                    $query->where('status', 'PUBLISHED')
                        ->with([
                            'photos',
                            'addonProducts' => function ($q) {
                                $q->with(['photos']);
                            }
                        ]);
                }])
                ->where('status', 'PUBLISHED')
                ->get();

                foreach ($categories as $category) {
                    foreach ($category->products as $product) {
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
                                ->where('sold_out', 0)
                                ->where('status', 'PUBLISHED')
                                ->with(['photos'])
                                ->get();

                            $product->setRelation('addonProducts', $fallbackAddons);
                        }
                    }
                }

                return View::make('components.shortcode-menu', compact('categories'))->render();

            case 'shortcodes_categories':
                $categories = ProductCategory::where('status', 'PUBLISHED')->get();

                return View::make('components.shortcode-categories', compact('categories'))->render();

            case 'shortcodes_newsletter':

                return View::make('components.newsletter-component')->render();

            case 'shortcodes_contact_form':

                return View::make('components.shortcode-contact')->render();

            case 'shortcodes_hotline':
                
                $headOffices = Branch::where('is_head_office', 1)->get();
                $branches = Branch::with('numbers')->where('is_head_office', 0)->get();
                return View::make('components.shortcode-hotline', compact('headOffices', 'branches'))->render();

            case 'shortcodes_branches':

                $headOffices = Branch::where('is_head_office', 1)->get();
                $branches = Branch::with('numbers')->where('is_head_office', 0)->get();
                $outlets = Branch::where('branch_type', 'Restaurant')->where('is_head_office', 0)->get();
                $malls = Branch::where('branch_type', 'Mall Based Foodcourt')->where('is_head_office', 0)->get();
                $kiosks = Branch::where('branch_type', 'Kiosk')->where('is_head_office', 0)->get();

                return View::make('components.shortcode-branches', compact('headOffices', 'branches', 'outlets', 'malls', 'kiosks'))->render();

            default:
                return ''; // Unknown shortcode
        }
    }
}
