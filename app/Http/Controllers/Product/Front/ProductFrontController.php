<?php

namespace App\Http\Controllers\Product\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductFrontController extends Controller
{
    public function show($slug)
    {
        $sales_history = 0;
        if(Auth::guest()) {
            $product = Product::whereSlug($slug)->where('status', 'PUBLISHED')->first();
        } else {
            $product = Product::whereSlug($slug)->where('status', '!=', 'UNEDITABLE')->first();
            $sales_history = $this->checkIfUserPurchasedTheItem($product->id);
        }

        $page = $product;
        if (empty($product)) {
            abort(404);
        }


        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.profile',compact('product', 'page','sales_history'));
    }

    public function show_forsale(){

        $products = DB::table('products')->where('for_sale', '1')->whereNull('deleted_at')->where('status','PUBLISHED')->where('for_sale_web','1')->where('is_misc','0')->select('name')->distinct()->get();

        $miscs = DB::table('products')->where('for_sale', '1')->whereNull('deleted_at')->where('status','PUBLISHED')->where('for_sale_web','1')->where('is_misc','1')->select('name')->distinct()->get();
        //$categories = Product::where('category_id','>',0)->where('for_sale_web','1')->select('category_id')->orderBy('category_id','asc')->distinct()->get();
        $categories = Product::where('category_id','>',0)->where('status','PUBLISHED')->where('for_sale_web','1')->select('category_id')->orderByRaw("FIELD(category_id, 1,13,18,12,2,17,19,20,21,4,5,6,8,3,22,26)")->distinct()->get();
        //\Log::info($categories);
        $page = new Page();
        $page->name = 'Order';

        // $d = '';
        // foreach($products as $product){
        //     $main = Product::info($product->name);
        //     if(empty($main)){
        //         $d.=$product->name."<br>";
        //     }
        // }
        // return $d;
        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.order',compact('products','page','miscs','categories'));

    }

    public function checkIfUserPurchasedTheItem($id){

        $rs = DB::select("SELECT d.*                  
                    FROM `ecommerce_sales_details` d 
                    left join ecommerce_sales_headers h on h.id=d.sales_header_id 
                    where h.payment_status='PAID' and d.product_id='".$id."'
                     ");
        if (empty($rs)) {
            return 0;
        }else{
            return 1;
        }

    }

    public function list(Request $request) {
        return $this->show_forsale();
        //logger($_GET['sort']);
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
                        $category = ProductCategory::find($categoryId);
                        $subCategories = $category->child_categories;
                        if ($subCategories && $subCategories->count()) {
                            $ids = $this->get_sub_categories_ids($ids, $subCategories);
                        }

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

        return view('theme.'.env('FRONTEND_TEMPLATE').'.ecommerce.product.product-list',compact('products','categories','max', 'selectedCategory'));

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

    public function categories($conditions=null){

        if($conditions){

        }
        else{
            $categories = DB::select('SELECT ifnull(c.name, "Uncategorized") as cat, ifnull(c.id,0) as cid,count(ifnull(c.id,0)) as total_products FROM `products` a left join product_categories c on c.id=a.category_id where a.deleted_at is null and a.status="PUBLISHED" GROUP BY c.name,c.id ORDER BY c.name');

//            $categories = ProductCategory::select('id', 'name')->where('parent_id', 0)->where('status', 'PUBLISHED')->orderBy('name')->get();
//            $product = Product::where('status', 'PUBLISHED')->where(function($model) {
//                $model->orWhere('category_id', null);
//                $model->orWhere('category_id', 0);
//            })->sortBy('name')->count();
//
//            if ($product) {
//                $uncategorized = new ProductCategory();
//                $uncategorized->id = 0;
//                $uncategorized->name = "Uncategorized";
//                $uncategorized->child_categories = null;
//
//                $categories->push($uncategorized);
//
//                $categories = $categories->sortBy(function ($category, $key) {
//                    return strtolower($category->name);
//                });
//            }


            $data = '<ul class="listing-category">';
            foreach($categories as $category) {
                $ul2 = '';
                if ($category->child_categories) {
                    $ul2 = '<ul>';
                    $ul3 = '';
                }
                $data .= '<li><a href="#" onclick="filter_category('.$category->id.')">'.$category->name.'</a><li>';
            }
            $data .= '</ul>';
        }

        return $data;
    }
}
