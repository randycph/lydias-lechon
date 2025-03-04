<?php

namespace App\Http\Controllers\Product\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use App\Product;
use App\Page;
use DB;
use Illuminate\Support\Facades\Auth;

class MenuFrontController extends Controller
{
    public function show()
    {
        $productCategories = ProductCategory::where('status', 'PUBLISHED')->where('category_id','>',1)->get();

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.menu',compact('productCategories'));
    }

    public function list(Request $request)
    {
        $page = new Page();
        $page->name = 'Menu';

        $productCategories = ProductCategory::where('status', 'PUBLISHED')->select('id','name')->orderBy('name','desc')->get();

        $miscs = DB::table('products')->where('for_sale', '1')->whereNull('deleted_at')->where('status','PUBLISHED')->where('for_sale_web','1')->where('is_misc','1')->select('name')->distinct()->get();


        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.menu',compact('productCategories','page','miscs'));

    }
    
    public function list_bk(Request $request) {
        //logger($_GET['sort']);
        $productCategories = ProductCategory::where('status', 'PUBLISHED')->select('id','name')->orderByRaw("FIELD(id, 1,13,18,12,2,17,19,20,21,4,5,6,8,3,9,10,11,22,26)")->distinct()->get();
        // $productCategories = Product::where('category_id','>',0)->where('status','PUBLISHED')->select('category_id')->orderByRaw("FIELD(category_id, 1,13,18,12,2,17,19,20,21,4,5,6,8,3)")->distinct()->get();
        $pageLimit = 10;

        if(isset($_GET['type']) || isset($_GET['limit']) || isset($_GET['sort'])){

            if(isset($_GET['type'])){

                if($_GET['type'] == 'searchbox'){

                    $products = Product::where(function($query){
                        $query->where('name','like','%'.$_GET['criteria'].'%')
                            ->orWhere('description','like','%'.$_GET['criteria'].'%');
                    })->whereStatus('PUBLISHED');


                }
                elseif($_GET['type'] == 'price'){

                    $products = Product::where('price','>=',$_GET['price_start'])->where('price','<=',$_GET['price_end'])->whereStatus('PUBLISHED');

                }
                elseif($_GET['type'] == 'rating'){

                    $prods = Product::whereStatus('PUBLISHED')->get();

                    $rs = [];
                    foreach($prods as $product){
                        if($product->rating == $_GET['criteria']){
                            array_push($rs,$product->id);
                        }
                    }

                    $products = Product::whereIn('id',$rs);



                }
                elseif($_GET['type'] == 'category'){
                    if($_GET['criteria'] == 0) {
                        $products = Product::where(function ($query) {
                            $query->whereNull('category_id')->orWhere('category_id', '=', 0);
                        })
                            ->whereStatus('PUBLISHED');
                    }
                    else {
                        $categoryId = (int) $request->criteria;
                        $ids = [$categoryId];
                        /*$category = ProductCategory::find($categoryId);
                        $subCategories = $category->child_categories;
                        if ($subCategories && $subCategories->count()) {
                            $ids = $this->get_sub_categories_ids($ids, $subCategories);
                        }*/

                        $products = Product::whereIn('category_id', $ids)->whereStatus('PUBLISHED');
                    }
                }
                else{
                    $products = Product::whereStatus('PUBLISHED');
                }

            }
            else{
                $products = Product::whereStatus('PUBLISHED');
            }


            if(isset($_GET['sort'])){
                if($_GET['sort'] == 'Price low to high'){
                    $products = $products->orderBy('price','asc');
                }
                elseif($_GET['sort'] == 'Price high to low'){
                    $products = $products->orderBy('price','desc');
                }
            }

            if(isset($_GET['limit'])){
                $pageLimit = $_GET['limit'];
            }

            $products = $products->orderBy('updated_at','desc')
                ->orderBy('id','desc')
                ->paginate($pageLimit);
        }
        else{
            $products = Product::whereStatus('PUBLISHED')
                ->orderBy('updated_at','desc')
                ->orderBy('id','desc')
                ->paginate($pageLimit);
        }

        /* End Search function */

        // Product Categories
        $categories = ProductCategory::select('id', 'name')->where('parent_id', 0)->where('status', 'PUBLISHED')->orderBy('name')->get();

        $product = Product::where('status', 'PUBLISHED')->where(function($model) {
            $model->orWhere('category_id', null);
            $model->orWhere('category_id', 0);
        })->orderBy('name')->count();

        if ($product) {
            $uncategorized = new ProductCategory();
            $uncategorized->id = 0;
            $uncategorized->name = "Uncategorized";
            $uncategorized->child_categories = null;

            $categories->push($uncategorized);

            $categories = $categories->sortBy(function ($category, $key) {
                return strtolower($category->name);
            });
        }
        // End Product Categories

        $max_product = Product::orderBy('price','desc')->first();
        $max = number_format($max_product->price, 0, '.', '');

        $selectedCategory = $request->has('criteria') ? $request->criteria : 'test';

        $page = new Page();
        $page->name = 'Menu';

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.menu',compact('products','categories','max', 'selectedCategory','productCategories','page'));

    }
    
    
    public function get_sub_categories_ids($ids, $categories)
    {
        $categoryIds = $categories->pluck('id');
        $ids = array_merge($ids, $categoryIds->toArray());
        foreach ($categoryIds as $id) {
            $subCategory = ProductCategory::find($id);
            $subSubCategories = $subCategory->child_categories;
            if ($subSubCategories && $subSubCategories->count()) {
                $ids = $this->get_sub_categories_ids($ids, $subSubCategories);
            }
        }

        return $ids;
    }

    public function featured_best_seller()
    {
        $featured_best_seller = Product::where('status', 'PUBLISHED')->where('is_featured', '1')->where('category_id', '3')->get();

        return view('theme.'.env('FRONTEND_TEMPLATE').'main',compact('featured_best_seller'));
    }

}
