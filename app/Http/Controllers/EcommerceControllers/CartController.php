<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Cart;
use App\Mail\SalesCompleted;
use App\Mail\SalesCompletedAdmin;
use App\Mail\SalesCompletedRegistered;
use Illuminate\Support\Facades\Mail;
use App\EcommerceModel\SalesPayment;
use App\EcommerceModel\Branch;
use App\EcommerceModel\Coupon;
use App\EcommerceModel\CouponCart;
use App\EcommerceModel\CouponSale;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesDetail;
use App\Helpers\Webfocus\Setting;
use App\Models\Product;
use App\Models\Deliverablecities;
use App\Models\User;
use App\Models\Sms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use App\EcommerceModel\GiftCertificate;
use App\Models\ActivityLog;
use App\Models\ProductDeliveryAddress;
use App\Models\Setting as ModelsSetting;
use Redirect;
use DateTime;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Fluent;


class CartController extends Controller
{

    public function index()
    {
        //
    }

    public function check_dateneeded(Request $request)
    {
        $rem = 0;
        $err = '';

        $x = explode(" - ", $request->dateneeded);

        $tym24 = date("H:i", strtotime($x[1]));


        //check if has cochinillo during holiday season
        $products_to_be_disabled = [165,166];
        $dates_to_be_disabled_for_products = ['2022-12-24','2022-12-25','2022-12-31'];
        $has_disabled_product=0;
        $has_disabled_date=0;
        if(in_array($x[0], $dates_to_be_disabled_for_products)){
            $has_disabled_date=1;
        }


        
        $disabled_order_dates = explode(",",Setting::info()->disable_pickup_dates);
        $disabled_delivery_dates = explode(",",Setting::info()->disable_delivery_dates);

        if(Setting::info()->disable_delivery == 1 && $request->delt == 'd2d' && in_array($x[0], $disabled_delivery_dates)){
            $rem = 1;  
            $err = Setting::info()->order_message;  
        }

        if(Setting::info()->disable_order == 1 && $request->delt == 'storepickup' && in_array($x[0], $disabled_order_dates)){
            $rem = 1;  
            $err = Setting::info()->order_message;  
        }

        //test
        // $rem = 1;
        // return response()->json([
        //     'err' => $request->dateneeded." xx ".$request->delt." xx ".$x[0],
        //     'remark' => $rem
        // ]);
        //endtest


        //check if time is between the operation time
        $current_time = $x[1];
        $open = "04:59";
        $close = "21:01";
        $date1 = new DateTime($tym24);
        $date2 = new DateTime($open);
        $date3 = new DateTime($close);
        if ($date1 > $date2 && $date1 < $date3){

        }
        else{
            $rem = 1;
            $err .= "<li>The time you've selected (".$x[1].") is beyond our operation time which is between 5AM - 9PM.</li>";
        }

        //check if time is more than 48 hrs
        $date = strtotime($x[0]." ".$x[1]);
        
        $hr_processing = Setting::info()->minimum_processing_hours;
        $sec_processing = $hr_processing  * 3600;

        $hr_processing_misc = Setting::info()->minimum_processing_hours_misc;
        $sec_processing_misc = $hr_processing_misc  * 3600;

        // check if cart has lechon baka
        $is_baka = 0;
        $is_misc = 0;
        if (auth()->guest()) {            
            $carts = collect(session('cart', []));
        } else {          
            $carts = Cart::where('user_id',Auth::id())->get();
        }
        foreach($carts as $cart){
            $cart_product = Product::whereId($cart->product_id)->first();
            if($cart->product_id == '42'){
                $is_baka = 1;
            }
            if($cart_product->is_misc == 0){
                $is_misc = 1;
            }

            if(in_array($cart->product_id, $products_to_be_disabled)){
                $has_disabled_product++;
            }
        }
        if($is_baka == 1){
            if($date < time() + 259200) {
                $rem = 1;
                $err .= "<li>The date and time you've selected (".$request->dateneeded.") is less than 72 hours from now. Our standard processing time for lechon baka is atleast 72 hours. However, you can still proceed with your order by contacting our store directly at our Call Hotline tab.</li>";
            } 
        }else{
            if($is_misc == 1){
                if($date < time() + $sec_processing) {
                    $rem = 1;
                    $err .= "<li>The date and time you've selected (".$request->dateneeded.") is less than ".$hr_processing." hours from now. Our standard processing time is atleast ".$hr_processing." hours. However, you can still proceed with your order by contacting our store directly at our Call Hotline tab.</li>";
                } 
            }
            else{
                if($date < time() + $sec_processing_misc) {
                    $rem = 1;
                    $err .= "<li>The date and time you've selected (".$request->dateneeded.") is less than ".$hr_processing_misc." hours from now. Our standard processing time is atleast ".$hr_processing_misc." hours. However, you can still proceed with your order by contacting our store directly at our Call Hotline tab.</li>";
                } 
            }
        }


        if($has_disabled_product == 1 && $has_disabled_date == 1){
            $rem = 1;
            $err .= "<li>To our Valued Customer,<br>
                        Happy Holidays! <br>
                        Due to Influx of orders for December 24-25 & December 31, 2022, we regret to inform you that our order of <b>Cochinillo</b> items is already Fully Booked. We are sorry for the inconvenience.<br>
                        You may update your order by clicking <a href='".route('cart.front.show')."'>here</a> or you may instead pick up your orders in our Lydia's Branches near you. Our staff will be more than happy to assist you.
                        We hope for your kind understanding.</li>";
        }




        return response()->json([
            'err' => $err,
            'remark' => $rem
        ]);

    }

    public function create()
    {
        //
    }

    public function store_old(Request $request)
    {
        $product = explode("|", $request->item);
        $paella = (isset($request->paella)) ? $request->paella_price : 0;

        if (auth()->check()) {
            $cart = Cart::where('product_id', $product[0])
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {
                $newQty = $cart->qty + $request->input('qty'.$request->loop_number);
                $cart->update([
                    'qty' => $newQty,
                    'price' => $product[1],
                    'paella_price' => $paella
                ]);
            } else {
                Cart::create([
                    'product_id' => $product[0],
                    'user_id' => Auth::id(),
                    'qty' => $request->input('qty'.$request->loop_number),
                    'price' => $product[1],
                    'paella_price' => $paella
                ]);
            }
        }else{
            $cart = session('cart', []);
            $not_exist = true;

            foreach ($cart as $key => $order) {
                if ($order->product_id == $request->product_id) {
                    $cart[$key]->qty = $request->input('qty'.$request->loop_number);
                    $cart[$key]->price = $product[1];
                    $cart[$key]->paella_price = $paella;

                    $not_exist = false;
                    break;
                }
            }

            if ($not_exist) {
                $order = new Cart();
                $order->product_id = $product[0];
                $order->qty = $request->input('qty'.$request->loop_number);
                $order->price = $product[1];
                $order->paella_price = $paella;

                array_push($cart, $order);
            }

            session(['cart' => $cart]);
        }
        if($request->is_checkout == 1)
            return redirect()->route('cart.front.show');
        else
            return back()->with('product_added', 'New Product has been added on your cart!');
    }

    public function updateQty(Request $request)
    {
        $product = Product::with('photos')->whereId($request->ac_item)->first();
        $mainProduct = $product;
        $photos = $product->photos()->where('is_primary', 1)->first();
        $photo = !empty($photos) ? asset('storage/products/'.$photos->path ) : '';
        $paella_cost = ($request->ac_paella == '1' ? ($product->paella_price) : 0);
        if (auth()->check()) {
            $cart = Cart::where('product_id', $request->ac_item)->where('paella', $request->ac_paella == '1')
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {
                $cart->update([
                    'qty' => $request->ac_qty,
                    'price' => $product->price,
                    'paella_price' => $paella_cost,
                ]);
            } else {
                Cart::create([
                    'product_id' => $request->ac_item,
                    'user_id' => Auth::id(),
                    'qty' => $request->ac_qty,
                    'price' => $mainProduct->price,
                    'paella_price' => $paella_cost,
                    'paella' => $request->ac_paella == '1',
                    'photo' => $photo,
                    'product' => $mainProduct,
                ]);
            }


            //misc items
            for($x =1; $x<=$request->misc_cntr;$x++){
                if($request->has('misc_id'.$x)){

                    $miscProduct = Product::whereId($request->input('misc_id'.$x))->first();
                    $cart = Cart::where('product_id', $request->input('misc_id'.$x))
                        ->where('user_id', Auth::id())
                        ->first();

                    if (!empty($cart)) {
                        $newQty = $request->input('misc_qty'.$x);
                        $save = $cart->update([
                            'qty' => $newQty,
                            'price' => $miscProduct->price
                        ]);
                    } else {
                        $save = Cart::create([
                            'product_id' => $request->input('misc_id'.$x),
                            'user_id' => Auth::id(),
                            'qty' => $request->input('misc_qty'.$x),
                            'price' => $miscProduct->price,
                            'photo' => $photo,
                            'product' => $miscProduct,
                            'paella_price' => 0
                        ]);
                    }

                }
            }


        } else {
            $cart = session('cart', []);
            $not_exist = true;

            foreach ($cart as $key => $order) {
                if (
                    $order->product_id == $request->ac_item &&
                    $order->paella == ($request->ac_paella == '1')
                ) {
                    $cart[$key]->qty = $request->ac_qty;
                    $cart[$key]->price = $product->price;
                    $cart[$key]->paella_price = $paella_cost;
                    $cart[$key]->photo = $photo;
                    $cart[$key]->product = $product;
                    $cart[$key]->paella = $request->ac_paella == '1';

                    $not_exist = false;
                    break;
                }
            }

            if ($not_exist) {
                $order = new Cart();
                $order->product_id = $request->ac_item;
                $order->qty = $request->ac_qty;
                $order->price = $product->price;
                $order->paella_price = $paella_cost;
                $order->photo = $photo;
                $order->product = $product;
                $order->coupon_code = '';
                $order->coupon_amount = '0';
                $order->paella = $request->ac_paella == '1';

                array_push($cart, $order);
            }

            session(['cart' => $cart]);

            //misc items
            for($x =1; $x<=$request->misc_cntr;$x++){
                if($request->has('misc_id'.$x)){

                    $cart = session('cart', []);
                    $not_exist = true;

                    foreach ($cart as $key => $order) {
                        if ($order->product_id == $request->input('misc_id'.$x)) {
                            $cart[$key]->qty = $request->input('misc_qty'.$x);
                            $cart[$key]->price = $product->price;
                            $cart[$key]->paella_price = 0;
                            $cart[$key]->photo = $photo;
                            $cart[$key]->product = $product;
                            $cart[$key]->paella = 0;

                            $not_exist = false;
                            break;
                        }
                    }

                    if ($not_exist) {
                        $order = new Cart();
                        $order->product_id = $request->input('misc_id'.$x);
                        $order->qty = $request->input('misc_qty'.$x);
                        $order->price = $product->price;
                        $order->paella_price = 0;
                        $order->photo = $photo;
                        $order->product = $product;
                        $order->paella = 0;

                        array_push($cart, $order);
                    }

                    session(['cart' => $cart]);
                }

            }

        }

        if($request->action == 'buynow'){
            return response()->json([
                'success' => true,
                'act' => 'buynow',
                'totalItems' => Setting::EcommerceCartTotalItems()
            ]);

        }else{
            return response()->json([
                'success' => true,
                'act' => 'addcart',
                'totalItems' => Setting::EcommerceCartTotalItems()
            ]);
        }
    }

    // public function store(Request $request)
    // {
    //     $product = Product::with('photos')->whereId($request->ac_item)->first();
    //     $photos = $product->photos()->where('is_primary', 1)->first();
    //     $photo = !empty($photos) ? asset('storage/products/'.$photos->path ) : '';
    //     $paella_cost = ($request->ac_paella == '1' ? ($product->paella_price * $request->ac_qty) : 0);
    //     if (auth()->check()) {

    //         $cart = Cart::where('product_id', $request->ac_item)->with('product')
    //             ->where('user_id', Auth::id())
    //             ->first();

    //         if (!empty($cart)) {
    //             $newQty = $cart->qty + $request->ac_qty;
    //             $save = $cart->update([
    //                 'qty' => $newQty,
    //                 'price' => $product->price,
    //                 'paella_price' => $paella_cost
    //             ]);
    //         } else {
    //             $save = Cart::create([
    //                 'product_id' => $request->ac_item,
    //                 'user_id' => Auth::id(),
    //                 'qty' => $request->ac_qty,
    //                 'price' => $product->price,
    //                 'paella_price' => $paella_cost,
    //                 'photo' => $photo,
    //             ]);
    //         }

    //         //misc items
    //         if ($request->has('misc_cntr') && (is_array($request->misc_cntr) && count($request->misc_cntr) > 0)) {
    //             foreach ($request->misc_cntr as $key => $misc) {
    //                 $miscProductId = $misc['misc_id'];
    //                 $miscQty = $misc['misc_qty'];
                    
    //                 $prod = Product::with([
    //                     'photos' => function ($q) {
    //                         $q->limit(1);
    //                     },
    //                 ])->where('id', $miscProductId)->first();
                
    //                 $image = $prod->photos()->first();
    //                 $image = !empty($image) ? asset('storage/products/'.$image->path ) : '';

    //                 $cart = Cart::where('product_id', $miscProductId)
    //                     ->where('user_id', Auth::id())
    //                     ->first();

    //                 if (!empty($cart)) {
    //                     logger($miscQty);
    //                     $save = $cart->update([
    //                         'qty' => $miscQty,
    //                         'price' => $prod->price
    //                     ]);
    //                 } else {
    //                     logger('11111');
    //                     $save = Cart::create([
    //                         'product_id' => $miscProductId,
    //                         'user_id' => Auth::id(),
    //                         'qty' => $miscQty,
    //                         'price' => $prod->price,
    //                         'photo' => $image,
    //                         'paella_price' => 0
    //                     ]);
    //                 }
    //             }
    //         }
    //     } else {
    //         $cart = session('cart', []);
    //         $not_exist = true;

    //         foreach ($cart as $key => $order) {
    //             if ($order->product_id == $request->ac_item) {
    //                 $cart[$key]->qty = $request->ac_qty;
    //                 $cart[$key]->price = $product->price;
    //                 $cart[$key]->paella_price = $paella_cost;
    //                 $cart[$key]->photo = $photo;
    //                 $cart[$key]->product = $product;

    //                 $not_exist = false;
    //                 break;
    //             }
    //         }

    //         if ($not_exist) {
    //             $order = new Cart();
    //             $order->product_id = $request->ac_item;
    //             $order->qty = $request->ac_qty;
    //             $order->price = $product->price;
    //             $order->paella_price = $paella_cost;
    //             $order->photo = $photo;
    //             $order->product = $product;
    //             $order->coupon_code = '';
    //             $order->coupon_amount = '0';

    //             array_push($cart, $order);
    //         }
            
    //         session(['cart' => $cart]);

    //         //misc items
    //         if ($request->has('misc_cntr') && (is_array($request->misc_cntr) && count($request->misc_cntr) > 0)) {
    //             foreach ($request->misc_cntr as $misc) {
    //                 $miscProductId = $misc['misc_id'];
    //                 $miscQty = $misc['misc_qty'];

    //                 $prod = Product::with([
    //                         'photos' => function ($q) {
    //                             $q->limit(1);
    //                         },
    //                     ])->where('id', $miscProductId)->first();
                    
    //                 $image = $prod->photos()->first();
    //                 $image = !empty($image) ? asset('storage/products/'.$image->path ) : '';
            
    //                 $miscExist = false;
            
    //                 foreach ($cart as $key => $order) {
    //                     if ($order->product_id == $miscProductId) {
    //                         $cart[$key]->qty = $miscQty;
    //                         $cart[$key]->price = $prod->price;
    //                         $cart[$key]->paella_price = 0;
    //                         $cart[$key]->photo = $image;
    //                         $cart[$key]->product = $prod;
    //                         $miscExist = true;
    //                         break;
    //                     }
    //                 }
            
    //                 if (!$miscExist) {
    //                     $order = new Cart();
    //                     $order->product_id = $miscProductId;
    //                     $order->qty = $miscQty;
    //                     $order->price = $prod->price;
    //                     $order->paella_price = 0;
    //                     $order->photo = $image;
    //                     $order->product = $prod;
            
    //                     array_push($cart, $order);
    //                 }
    //             }
            
    //             session(['cart' => $cart]);
    //         }
    //     }

    //     if($request->action == 'buynow'){
    //         return response()->json([
    //             'success' => true,
    //             'act' => 'buynow',
    //             'totalItems' => Setting::EcommerceCartTotalItems()
    //         ]);

    //     }else{
    //         return response()->json([
    //             'success' => true,
    //             'act' => 'addcart',
    //             'totalItems' => Setting::EcommerceCartTotalItems()
    //         ]);
    //     }
    // }

    public function store(Request $request)
    {
        $product = Product::with('photos')->whereId($request->ac_item)->first();
        $photos = $product->photos()->where('is_primary', 1)->first();
        $photo = !empty($photos) ? asset('storage/products/'.$photos->path ) : '';
        $paella_cost = ($request->ac_paella == '1' ? ($product->paella_price) : 0);
        $has_paella = $request->ac_paella == '1' ? 1 : 0;

        if (auth()->check()) {

            $cart = Cart::where('product_id', $request->ac_item)->with('product')
                ->where('paella', $has_paella)
                ->where('user_id', Auth::id())
                ->first();

            if ($cart) {
                // Update qty if same product + paella combo already exists
                $cart->update([
                    'qty' => $cart->qty + $request->ac_qty,
                    'price' => $product->price,
                    'paella_price' => $paella_cost,
                ]);
            } else {
                // Otherwise, create new row
                Cart::create([
                    'product_id' => $request->ac_item,
                    'user_id' => Auth::id(),
                    'qty' => $request->ac_qty,
                    'price' => $product->price,
                    'paella_price' => $paella_cost,
                    'photo' => $photo,
                    'paella' => $has_paella,
                ]);
            }

            //misc items
            if ($request->has('misc_cntr') && (is_array($request->misc_cntr) && count($request->misc_cntr) > 0)) {
                foreach ($request->misc_cntr as $key => $misc) {
                    $miscProductId = $misc['misc_id'];
                    $miscQty = $misc['misc_qty'];
                    
                    $prod = Product::with([
                        'photos' => function ($q) {
                            $q->limit(1);
                        },
                    ])->where('id', $miscProductId)->first();
                
                    $image = $prod->photos()->first();
                    $image = !empty($image) ? asset('storage/products/'.$image->path ) : '';

                    $cart = Cart::where('product_id', $miscProductId)
                        ->where('user_id', Auth::id())
                        ->first();

                    if (!empty($cart)) {
                        $save = $cart->update([
                            'qty' => $cart->qty + $miscQty,
                            'price' => $prod->price,
                            'paella' => 0
                        ]);
                    } else {
                        $save = Cart::create([
                            'product_id' => $miscProductId,
                            'user_id' => Auth::id(),
                            'qty' => $miscQty,
                            'price' => $prod->price,
                            'photo' => $image,
                            'paella_price' => 0,
                            'paella' => 0,
                        ]);
                    }
                }
            }
        } else {

            $cart = session('cart', []);
            $not_exist = true;
            $has_paella = $request->ac_paella == '1' ? 1 : 0;

            foreach ($cart as $key => $order) {
                // Compare BOTH product_id AND has_paella flag
                if (
                    $order->product_id == $request->ac_item &&
                    (isset($order->has_paella) ? $order->has_paella : 0) == $has_paella
                ) {
                    // Same product and same paella option → increment qty
                    $cart[$key]->qty += $request->ac_qty;
                    // Optionally update other details
                    $cart[$key]->price = $product->price;
                    $cart[$key]->paella_price = $paella_cost;
                    $cart[$key]->photo = $photo;
                    $cart[$key]->product = $product;
                    $cart[$key]->paella = $has_paella;
                    $not_exist = false;
                    break;
                }
            }

            if ($not_exist) {
                $order = new Cart();
                $order->product_id = $request->ac_item;
                $order->qty = $request->ac_qty;
                $order->price = $product->price;
                $order->paella_price = $paella_cost;
                $order->photo = $photo;
                $order->product = $product;
                $order->has_paella = $has_paella;
                $order->coupon_code = '';
                $order->coupon_amount = '0';
                $order->paella = $has_paella;

                array_push($cart, $order);
            }

            session(['cart' => $cart]);

            //misc items
            if ($request->has('misc_cntr') && (is_array($request->misc_cntr) && count($request->misc_cntr) > 0)) {
                foreach ($request->misc_cntr as $misc) {
                    $miscProductId = $misc['misc_id'];
                    $miscQty = $misc['misc_qty'];

                    $prod = Product::with([
                            'photos' => function ($q) {
                                $q->limit(1);
                            },
                        ])->where('id', $miscProductId)->first();
                    
                    $image = $prod->photos()->first();
                    $image = !empty($image) ? asset('storage/products/'.$image->path ) : '';
            
                    $miscExist = false;
            
                    foreach ($cart as $key => $order) {
                        if ($order->product_id == $miscProductId) {
                            $cart[$key]->qty += $miscQty;
                            $cart[$key]->price = $prod->price;
                            $cart[$key]->paella_price = 0;
                            $cart[$key]->photo = $image;
                            $cart[$key]->product = $prod;
                            $cart[$key]->paella = 0;
                            $miscExist = true;
                            break;
                        }
                    }
            
                    if (!$miscExist) {
                        $order = new Cart();
                        $order->product_id = $miscProductId;
                        $order->qty += $miscQty;
                        $order->price = $prod->price;
                        $order->paella_price = 0;
                        $order->photo = $image;
                        $order->product = $prod;
                        $order->paella = 0;
            
                        array_push($cart, $order);
                    }
                }
            
                session(['cart' => $cart]);
            }
        }

        if($request->action == 'buynow'){
            return response()->json([
                'success' => true,
                'act' => 'buynow',
                'totalItems' => Setting::EcommerceCartTotalItems()
            ]);

        }else{
            return response()->json([
                'success' => true,
                'act' => 'addcart',
                'totalItems' => Setting::EcommerceCartTotalItems()
            ]);
        }
    }


    public function getTotalItems(){

    }

    public function view()
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id',Auth::id())->get();
            $totalProducts = $cart->count();
        } else {
            $cart = session('cart', []);
            $totalProducts = count(session('cart', []));
        }

        $page = new Page();
        $page->name = 'Cart';

        return view('theme.'.config('app.frontend_template').'.ecommerce.cart.cart', compact('cart', 'totalProducts','page'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function remove_product(Request $request)
    {
        if (auth()->check()) {
            $delete = Cart::whereId($request->product_remove_id)->delete();
        } else {
            $cart = session('cart', []);
            $index = (int) $request->product_remove_id;
            if (isset($cart[$index])) {
                unset($cart[$index]);
            }
            session(['cart' => $cart]);
        }

        return back();
    }

    public function batch_update(Request $request)
    {
        if (auth()->check()) {
            if (Cart::where('user_id', auth()->id())->count() == 0) {
                return redirect()->route('product.front.list');
            }

            // for ($x = 1; $x <= $request->total_products; $x++) {
            //     Cart::whereId($request->record_id[$x])->where('user_id', auth()->id())->update([
            //         'qty' => $request->quantity[$x]
            //     ]);
            // }

            foreach ($request->record_id as $index => $recordId) {
                Cart::whereId($recordId)
                    ->where('user_id', auth()->id())
                    ->update([
                        'qty' => $request->quantity[$index] ?? 1,
                    ]);
            }

            return redirect()->route('checkout');
        } else {
            $cart = session('cart', []);

            for ($x = 1; $x <= $request->total_products; $x++) {
                foreach ($cart as $key => $order) {
                    if ($order->product_id == $request->record_id[$x]) {
                        $cart[$key]->qty = $request->quantity[$x];
                        break;
                    }
                }
            }

            session(['cart' => $cart]);

            if($request->customer_type == 1){
                return redirect()->route('cart.front.checkout-as-guest');
            } else {
                return redirect()->route('customer-front.login');
            }
        }
    }

    private function validateProcessingHours($date, $time, $minimumHours)
    {
        try {
            $requested = Carbon::parse($date . ' ' . $time);
        } catch (\Exception $e) {
            return false;
        }

        $now = Carbon::now();

        return $requested->greaterThanOrEqualTo(
            $now->copy()->addHours($minimumHours)
        );
    }


    public function save_sales(Request $request) 
    {
        $carts = auth()->check()
            ? Cart::where('user_id', auth()->id())->with('product')->get()
            : collect(session('cart', []));
        
        $setting = ModelsSetting::first();
        $minimum_processing_hours = $setting ? $setting->minimum_processing_hours : 24;
        $minimum_processing_hours_misc = $setting ? $setting->minimum_processing_hours_misc : 12;
        $minimum_processing_hours_baka = $setting ? $setting->minimum_processing_hours_baka : 72;

        $hasLechon = $carts->contains(fn($c) => $c->product->category_id == 1);
        $hasBaka   = $carts->contains(fn($c) => $c->product->slug == 'lechon-baka');
        $hasMisc   = $carts->contains(fn($c) => $c->product->is_misc == 1);

        $minHours = $minimum_processing_hours;

        if ($hasBaka) {
            $minHours = $minimum_processing_hours_baka;
        } elseif ($hasMisc) {
            $minHours = $minimum_processing_hours_misc;
        }

        $validator = Validator::make($request->all(), [
            'mobile' => ['required','regex:/^(09|\+639)\d{9}$/'],
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
        ], [
            'mobile.regex' => 'The mobile number must start with 09 or +639 and be followed by 9 digits.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$request->has('deliveries') || empty($request->deliveries)) {

            if (!$request->need_date || !$request->need_time) {
                if (!$request->need_date) {
                    return response()->json([
                        'errors' => [
                            'need_date' => ['Date is required']
                        ]
                    ], 422);
                }

                if (!$request->need_time) {
                    return response()->json([
                        'errors' => [
                            'need_time' => ['Time is required']
                        ]
                    ], 422);
                }
            }

            if (!$this->validateProcessingHours(
                $request->need_date,
                $request->need_time,
                $minHours
            )) {
                return response()->json([
                    'errors' => [
                        'need_date' => ['Selected date/time does not meet minimum processing hours.']
                    ]
                ], 422);
            }
        }

        if (!$request->has('deliveries') && $request->shipping_type == 'delivery') {

            if (!$request->delivery_address)
                return response()->json(['errors' => ['delivery_address' => ['Address is required.']]], 422);

            if (!$request->province)
                return response()->json(['errors' => ['province' => ['Province is required.']]], 422);

            if (!$request->city)
                return response()->json(['errors' => ['city' => ['City is required.']]], 422);

            if (!$request->location)
                return response()->json(['errors' => ['location' => ['Barangay is required.']]], 422);
        }

        if ($request->has('deliveries')) {

            $deliveries = json_decode($request->deliveries ?? '[]');

            if (!is_array($deliveries)) {
                return response()->json([
                    'errors' => ['deliveries' => ['Invalid delivery format.']]
                ], 422);
            }

            foreach ($deliveries as $index => $delivery) {

                if (empty($delivery->orders)) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.orders" =>
                                ["Please assign at least one order."]
                        ]
                    ], 422);
                }

                if (empty($delivery->need_time)) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.need_time" =>
                                ["Time is required for delivery ".($index+1)."."]]
                    ], 422);
                }

                if (empty($delivery->need_date)) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.need_date" =>
                                ["Date is required for delivery ".($index+1)."."]]
                    ], 422);
                }   

                if (empty($delivery->address))
                    return response()->json(['errors' => ["deliveries.$index.address" => ["Address is required."]]], 422);

                if (empty($delivery->province))
                    return response()->json(['errors' => ["deliveries.$index.province" => ["Province is required."]]], 422);

                if (empty($delivery->city))
                    return response()->json(['errors' => ["deliveries.$index.city" => ["City is required."]]], 422);

                if (empty($delivery->location))
                    return response()->json(['errors' => ["deliveries.$index.location" => ["Barangay is required."]]], 422);

                if (empty($delivery->name))
                    return response()->json(['errors' => ["deliveries.$index.name" => ["Contact person is required."]]], 422);

                if (empty($delivery->phone)) {
                    return response()->json(['errors' => ["deliveries.$index.phone" => ["Contact number is required."]]], 422);
                }
                    
                if (!preg_match('/^(09|\+639)\d{9}$/', $delivery->phone)) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.phone" => ["Invalid mobile number format."]
                        ]
                    ], 422);
                }

                if (empty($delivery->need_date) || empty($delivery->need_time))
                    return response()->json(['errors' => ["deliveries.$index.need_date" => ["Date and time are required."]]], 422);


                // PROCESSING HOURS PER DELIVERY

                $deliveryMinHours = 0;

                foreach ($delivery->orders as $order) {

                    if (!isset($order->product)) continue;

                    if ($order->product->slug === 'lechon-baka') {
                        $deliveryMinHours = max($deliveryMinHours, $minimum_processing_hours_baka);

                    } elseif ($order->product->is_misc == 1) {
                        $deliveryMinHours = max($deliveryMinHours, $minimum_processing_hours_misc);

                    } elseif ($order->product->category_id == 1) {
                        $deliveryMinHours = max($deliveryMinHours, $minimum_processing_hours);
                    }
                }

                if (!$this->validateProcessingHours(
                    $delivery->need_date,
                    $delivery->need_time,
                    $deliveryMinHours
                )) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.need_date" =>
                                ["Delivery ".($index+1)." does not meet processing hours requirement."]
                        ]
                    ], 422);
                }
            }
        }

        if (auth()->guest()) {
            $customer_name = $request->name ?? 'Guest';

            $user = User::where('email', $request->email)->first();
            
            if ($user) {
                $existingUser = User::where('email', $request->email)
                    ->first();
                if (!$existingUser) {
                    $newEmail = $this->generateUniqueEmail($request->email);
                    $request->merge(['email' => $newEmail]);
                }
            }

            $firstName = explode(' ', trim($request->name))[0] ?? 'Guest';
            $lastName = trim(str_replace($firstName, '', trim($request->name))) ?: 'Guest';

            // create new guest user
            $user = User::create([
                'name' => $customer_name,
                'contact_mobile' => $request->mobile,
                'email' => $request->email ?? 'wsiphproduction@gmail.com',
                'valid_email' => $request->email ?? null,
                'registration_type' => 'guest',
                'registration_source' => 'Guest',
                'password' => Hash::make(Str::random(10)),
                'firstname' => $firstName,
                'lastname' => $lastName,
                'is_active' => 1,
                'role_id' => 6
            ]);

            // if ($user) {
            //     // update user info
            //     $user->update([
            //         'name' => $request->name ?? 'Guest',
            //         'contact_mobile' => $request->mobile,
            //         'firstname' => $request->name,
            //         'lastname' => $request->name,
            //     ]);
            // } else {
            //     // create new guest user
            //     $user = User::create([
            //         'name' => $request->name ?? 'Guest',
            //         'contact_mobile' => $request->mobile,
            //         'email' => $request->email ?? 'wsiphproduction@gmail.com',
            //         'registration_type' => 'guest',
            //         'registration_source' => 'Guest',
            //         'password' => Hash::make(Str::random(10)),
            //         'firstname' => $request->name,
            //         'lastname' => $request->name,
            //         'is_active' => 1
            //     ]);
            // }

            // $user = User::create([
            //     'name' => $request->name ?? 'Guest',
            //     'contact_mobile' => $request->mobile,
            //     'email' => $request->email ?? 'wsiphproduction@gmail.com',
            //     'registration_type' => 'guest',
            //     'registration_source' => 'Guest',
            //     'password' => Hash::make(Str::random(10)),
            //     'firstname' => $request->name,
            //     'lastname' => $request->name,
            //     'is_active' => 1
            // ]);

            $carts = (session('cart', []));

            // check if theres a product with the of 178 (lechon baka) in the cart

            $bakaCart = collect($carts)->firstWhere('product_id', 178);
            $bakaQty = $bakaCart ? $bakaCart['qty'] : 0;

            if ($bakaCart) {
                $bakaProduct = Product::whereId(270)->first();

                $order = new Cart();
                $order->product_id = 270;
                $order->qty = $bakaQty;
                $order->price = $bakaProduct->price ?? 0;
                $order->paella_price = 0;
                $order->photo = null;
                $order->product = $bakaProduct;

                array_push($carts, $order);

                session(['cart' => $carts]);
            }

            $sessionId = $request->session()->getId();

            ActivityLog::where('session_id', $sessionId)
                ->whereNull('session_owner_id')
                ->update([
                    'session_owner_id' => $user->id,
                    'created_by' => $user->id,
                ]);
            
            $carts = collect(session('cart', []));

        } else {
            $user = auth()->user();
            $customer_name = $user->name;
            $carts = Cart::where('user_id', $user->id)->get();

            $bakaCart = $carts->firstWhere('product_id', 178);
            $bakaQty = $bakaCart ? $bakaCart->qty : 0;

            if ($bakaCart) {
                Cart::updateOrCreate(
                    [
                        'product_id' => 270,
                        'user_id' => $user->id,
                    ],
                    [
                        'qty' => $bakaQty,
                        'price' => Product::whereId(270)->first()->price ?? 0,
                        'photo' => null,
                        'product' => Product::whereId(270)->first(),
                        'paella_price' => 0
                    ]
                );
            }

            $customer_name = $user->firstname . ' ' . $user->lastname;
            $carts = Cart::where('user_id',$user->id)->get();
        }

        if ($carts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty. Please add items to your cart before proceeding to checkout.'
            ]);
        }
        $bakaServiceFee = Product::whereId(270)->first()->price ?? 0;

        $coupon_data = json_decode($request->input('coupon_data'), true);

        if (!empty($coupon_data)) {
            foreach ($coupon_data as $coupon) {
                if (!empty($coupon['free_products'])) {
                    foreach ($coupon['free_products'] as $freeProduct) {
                        // Check if already added
                        $alreadyFree = $carts->first(function ($item) use ($freeProduct) {
                            return (isset($item->is_free_product) && $item->is_free_product === true)
                                && ($item->product_id == $freeProduct['id']);
                        });

                        if (!$alreadyFree) {
                            $cartItem = new Cart();
                            $cartItem->id = 'free_' . $freeProduct['id'];
                            $cartItem->product_id = $freeProduct['id'];
                            $cartItem->name = $freeProduct['name'];
                            $cartItem->price = 0;
                            $cartItem->qty = 1;
                            $cartItem->is_free_product = true;
                            $cartItem->coupon_code = $coupon['code'];

                            $product = new Fluent([
                                'id' => $freeProduct['id'],
                                'name' => $freeProduct['name'],
                                'photos' => collect($freeProduct['photos'] ?? []),
                                'price' => 0,
                                'category_id' => $freeProduct['category_id'] ?? null,
                                'uom' => $freeProduct['uom'] ?? '',
                                'size' => $freeProduct['size'] ?? '',
                                'no_of_pax' => $freeProduct['no_of_pax'] ?? '',
                                'paella_price' => $freeProduct['paella_price'] ?? 0,
                            ]);

                            $cartItem->setRelation('product', $product);

                            $carts->push($cartItem);
                        }
                    }
                }
            }
        }

        //dd($request);
        $dn = explode(" - ", $request->need_date . ' - ' . $request->need_time);
        $date_needed = date('Y-m-d H:i:s',strtotime($dn[0]." ".$dn[1]));
        $deposit = $request->deposit;
        $delivery_fee = 0;
        if ($request->shipping_type == 'pickup') {
            $delivery_type='Store Pickup';
            $outlet = $request->delivery_branch;
            $customer_delivery_adress = $request?->delivery_branch ?? $request->delivery_address;            
            $customer_contact_number = $request->mobile;
            $customer_location = '';
            $contact_person = $request->name;
        } else {
            $delivery_type='Door to door delivery';
            $delivery_fee = $request->delivery_fee;
            if ($request->location == 'Other') {
                $customer_delivery_adress = $request->delivery_address;
            } else {
                $customer_delivery_adress = $request->delivery_address;  
            }
                     
            $customer_contact_number = $request->mobile;
            $customer_location = $request->delivery_address;
            $contact_person = $request->name;
            $outlet = '';
        }

        $req = $request->all();
        
        if ($request->has('deliveries')) {
            $deliveries = json_decode($request->deliveries ?? '[]', true);
            if ($deliveries && count($deliveries) > 0) {
                $delivery_fee = array_reduce($deliveries, function ($carry, $item) {
                    return $carry + (float) ($item['delivery_fee'] ?? 0);
                }, 0);
            }
        }

        if ($delivery_type == 'Door to door delivery') {
            $req['force_fee'] = true;

            if ($delivery_fee == 0 || $delivery_fee == '' || $delivery_fee == null) {
                $shippingRate = $this->get_shipping_fee(new Request($req));

                $delivery_fee = $shippingRate;
            }
        }

        $lechonBakaSevice = $request->isBaka == 1 ? $request->lechon_baka_service : 0;
        $bakaProduct = Product::whereId(270)->first();

        $qty = 0;
        foreach (collect($carts) as $cart) {
            if ($cart->product_id == 178) {
                $qty = $cart->qty;
                break;
            }
        }

        $totalPrice = $request->order_amount + ( $bakaProduct->price * $qty );
        $discount = 0;
        // $delivery_fee = $request->shipping_type == 'pickup' ? 0 : $request->delivery_fee;
        $netAmount = $totalPrice + $delivery_fee;
        $totalPrice = (float) $totalPrice + (float) $delivery_fee;

        $couponsList = json_decode($request->coupons, true);
        if (($couponsList && count($couponsList) > 0) && $request->discount_amount) {
            $discount = (float) $request->discount_amount;
            $netAmount = (float) $totalPrice - (float) $request->discount_amount;
        }
        $ran = microtime();
        $today = getdate();
        $requestId = $today[0].substr($ran, 2,6);
        $member = $user;
        
        if($request->hasCookie('origin')) {
            $origin = Cookie::get('origin');
        } else {
            $origin = NULL;
        }
        
        if(Carbon::now()->format('H:i') > Setting::info()->cutoff){
            $forecast_date = date('Y-m-d', strtotime('+1 days'));
        } else {
            $forecast_date = date('Y-m-d');
        }

        if (session()->has('edit_sales_header_id') && !empty(session()->get('edit_sales_header_id'))) {
            $salesHeader = SalesHeader::find(session()->get('edit_sales_header_id'));
            SalesHeader::where('id', $salesHeader->id)->update([
                'user_id' => $user->id,
                'email' => $request->email ?? $user->email,
                'customer_name' => $customer_name,
                'customer_contact_number' => $customer_contact_number,
                'customer_address' => $customer_delivery_adress,
                'customer_delivery_adress' => $customer_delivery_adress,
                'city' => $request->city ?? '',
                'province' => $request->province ?? '',
                'barangay' => $request->location ?? '',
                'delivery_tracking_number' => '',
                'delivery_type' => $delivery_type,
                'delivery_fee_amount' => $delivery_fee,
                'order_source' => 'Web',
                'delivery_branch' => $delivery_type == 'Door to door delivery' ? 'Tandang Sora Delivery' : '',
                'gross_amount' => $totalPrice,
                'tax_amount' => 0,
                'net_amount' => $netAmount,
                'discount_amount' => $discount,
                'payment_status' => $request->order_amount <= 0 ? 'PAID' : 'PENDING',
                'delivery_status' => '',
                'status' => 'active',
                'currency' => 'PHP',
                'customer_location' => $customer_location,
                'instruction' => $request->instruction,
                'agent' => $request->agent,
                'contact_person' => $contact_person,
                'outlet' => $outlet,
                'origin' => $origin,
                'forecast_date' => $forecast_date,
                'updated_at' => $salesHeader->created_at,
                'is_multiple_address' => ($request->has('deliveries') && count(json_decode($request->deliveries)) > 0) ? 1 : 0,
                'is_new_order' => 1,
                'has_sub' => ($request->has('deliveries') && count(json_decode($request->deliveries)) > 0) ? 1 : 0,
                'lechon_baka_service' => $lechonBakaSevice,
                'has_baka' => $request->isBaka == 1 ? 1 : 0,
            ]);
            $salesHeader = SalesHeader::find($salesHeader->id);
            if (!$salesHeader) {
                session()->forget('edit_sales_header_id');
                return redirect()->back()->withErrors(['error' => 'A problem has occurred.']);
            }
            $details = SalesDetail::where('sales_header_id', $salesHeader->id)->get();
            if ($details) {
                foreach ($details as $detail) {
                    $detail->delete();
                }
            }
            $couponCart = CouponCart::where('sales_header_id', $salesHeader->id)->get();
            if ($couponCart) {
                foreach ($couponCart as $coupon) {
                    $coupon->delete();
                }
            }
            $productDeliveryAddress = ProductDeliveryAddress::where('sales_header_id', $salesHeader->id)->get();
            if ($productDeliveryAddress) {
                foreach ($productDeliveryAddress as $address) {
                    $address->delete();
                }
            }
            $salesPayment = SalesPayment::where('sales_header_id', $salesHeader->id)->get();
            if ($salesPayment) {
                foreach ($salesPayment as $payment) {
                    $payment->delete();
                }
            }
            $ouponSale  = CouponSale::where('sales_header_id', $salesHeader->id)->get();
            if ($ouponSale) {
                foreach ($ouponSale as $sale) {
                    $sale->delete();
                }
            }

            session()->forget('edit_sales_header_id');
        } else {
            $salesHeader = SalesHeader::create([
                'user_id' => $user->id,
                'email' => $request->email ?? $user->email,
                'order_number' => $requestId,
                'customer_name' => $customer_name,
                'customer_contact_number' => $customer_contact_number,
                'customer_address' => $customer_delivery_adress,
                'customer_delivery_adress' => $customer_delivery_adress,
                'city' => $request->city ?? '',
                'province' => $request->province ?? '',
                'barangay' => $request->location ?? '',
                'delivery_tracking_number' => '',
                'delivery_type' => $delivery_type,
                'delivery_fee_amount' => $delivery_fee,
                'order_source' => 'Web',
                'gross_amount' => $request->order_amount + ( $bakaProduct?->price * $qty ),
                'delivery_branch' => $delivery_type == 'Door to door delivery' ? 'Tandang Sora Delivery' : '',
                'tax_amount' => 0,
                'net_amount' => $netAmount,
                'discount_amount' => $discount,
                'payment_status' => $request->order_amount <= 0 ? 'PAID' : 'PENDING',
                'delivery_status' => '',
                'status' => 'active',
                'currency' => 'PHP',
                'customer_location' => $customer_location,
                'instruction' => $request->instruction,
                'agent' => $request->agent,
                'contact_person' => $contact_person,
                'outlet' => $outlet,
                'origin' => $origin,
                'forecast_date' => $forecast_date,
                'is_multiple_address' => ($request->has('deliveries') && count(json_decode($request->deliveries)) > 0) ? 1 : 0,
                'is_new_order' => 1,
                'has_sub' => ($request->has('deliveries') && count(json_decode($request->deliveries)) > 0) ? 1 : 0,
                'lechon_baka_service' => $lechonBakaSevice,
                'has_baka' => $request->isBaka == 1 ? 1 : 0,
            ]);
        }

        if ($request->order_amount <= 0) {
            $salesHeader->isConfirm = 1;
            $salesHeader->confirmed_by = 'Customer';
            $salesHeader->confirmed_on = date('Y-m-d H:i:s');
            $salesHeader->confirm_remarks = 'Auto confirm via Checkout';
            $salesHeader->save();
        }

        $couponCode = null;

        $couponsList = json_decode($request->coupons, true);
        if ($couponsList && $request->discount_amount) {
            if (count($couponsList) > 0) {
                foreach ($couponsList as $coupon) {
                    $couponCode = Coupon::whereRaw('LOWER(coupon_code) = ?', [strtolower($coupon['code'])])
                        ->where('status', 'ACTIVE')
                        ->first();

                    if ($couponCode) {
                        CouponCart::create([
                            'coupon_id' => $couponCode?->id,
                            'customer_id' => $user->id,
                            'total_usage' => 1,
                            'status' =>  0,
                            'sales_header_id' => $salesHeader->id,
                            'coupon_code' => $couponCode->coupon_code,
                            'discount_used' => $coupon['discount_used']
                        ]);
                    }
                }
            }
        }

        $formattedOrderNumber = sprintf('%07d', $salesHeader->id);

        $lastOrder = SalesHeader::whereNull('parent_sales_header_id')
            ->whereRaw('order_number REGEXP "^[0-9]{7}$"')
            ->max('order_number');

        $nextOrder = $lastOrder ? intval($lastOrder) + 1 : 1;

        $orderNumber = sprintf('%07d', $nextOrder);

        $salesHeader->update([
            'order_number' => $orderNumber
        ]);
        $salesHeader->order_number = $orderNumber;
        $salesHeader->save();

        if ($request->has('deliveries')) {
            $deliveries = json_decode($request->deliveries ?? '');
            if ($deliveries && count($deliveries) > 0) {
                foreach ($deliveries as $k => $delivery) {
                    if (!empty($delivery->orders)) {

                        $subSalesHeader = SalesHeader::create([
                            'user_id' => $user->id,
                            'parent_sales_header_id' => $salesHeader->id,
                            'email' => $request->email ?? $user->email,
                            'order_number' => $requestId,
                            'customer_name' => $customer_name,
                            'customer_contact_number' => $delivery->phone,
                            'customer_address' => $delivery->address,
                            'customer_delivery_adress' => $delivery->address,
                            'delivery_tracking_number' => '',
                            'delivery_type' => 'Door to door delivery',
                            'delivery_fee_amount' => $delivery->delivery_fee,
                            'order_source' => 'Web',
                            'gross_amount' => $request->order_amount + ( $bakaProduct->price * $qty ),
                            'delivery_branch' => 'Tandang Sora Delivery',
                            'tax_amount' => 0,
                            'net_amount' => $netAmount,
                            'discount_amount' => $discount,
                            'payment_status' => $request->order_amount <= 0 ? 'PAID' : 'PENDING',
                            'delivery_status' => '',
                            'status' => 'active',
                            'currency' => 'PHP',
                            'customer_location' => $customer_location,
                            'instruction' => $request->instruction,
                            'agent' => $request->agent,
                            'contact_person' => $delivery->name,
                            'outlet' => $outlet,
                            'origin' => $origin,
                            'forecast_date' => $forecast_date,
                            'is_multiple_address' => 0,
                            'is_new_order' => 1,
                            'is_sub' => 1,
                            'has_baka' => $delivery?->isBaka ? 1 : 0,
                            'lechon_baka_service' => $delivery?->lechon_baka_service ?? 0,
                            // 'date_needed' => $delivery->need_date . ' ' . $delivery->need_time,
                            // 'delivery_fee' => $delivery->delivery_fee,
                            // 'note' => $delivery->note,
                            'city' => $delivery->city ?? '',
                            'province' => $delivery->province ?? '',
                            'barangay' => $delivery->location ?? '',
                        ]);

                        if ($request->order_amount <= 0) {
                            $subSalesHeader->isConfirm = 1;
                            $subSalesHeader->confirmed_by = 'Customer';
                            $subSalesHeader->confirmed_on = date('Y-m-d H:i:s');
                            $subSalesHeader->confirm_remarks = 'Auto confirm via Checkout';
                            $subSalesHeader->save();
                        }

                        // $subSalesHeader->order_number = sprintf('%07d', $salesHeader->id) . '-' . ($k+1);
                        // $subSalesHeader->order_number = sprintf('%07d', $subSalesHeader->id);
                        $letter = strtoupper(chr(65 + $k));
                        $subSalesHeader->order_number = sprintf('%07d', $salesHeader->id) . '-' . $letter;
                        $subSalesHeader->save();

                        ProductDeliveryAddress::create([
                            'sales_header_id' => $subSalesHeader->id,
                            'address' => $delivery->address,
                            'contact_person' => $delivery->name,
                            'contact_tel' => $delivery->phone,
                            'qty' => array_sum(array_column($delivery->orders, 'qty')),
                            'location' => $delivery->city . ', ' . $delivery->province,
                            'delivery_fee' => $delivery->delivery_fee,
                            'delivery_date' => $delivery->need_date,
                            'delivery_time' => $delivery->need_time,
                            'note' => $delivery->note,
                            'branch' => $request->delivery_branch,
                            'products' => json_encode($delivery->orders),
                            'receive_sms' => $delivery->sms ? 1 : 0,
                            'paella_price' =>
                                (isset($delivery->orders[0]->paella) && $delivery->orders[0]->paella === true && !empty($delivery->orders[0]->product->paella_price))
                                    ? $delivery->orders[0]->product->paella_price
                                    : 0,
                            'province' => $delivery->province,
                            'city' => $delivery->city,
                            'barangay' => $delivery->location ?? '',
                            'has_baka' => $delivery?->isBaka ? 1 : 0,
                            'lechon_baka_service' => $delivery?->lechon_baka_service ?? 0,
                        ]);

                        if ($delivery->phone && $delivery->sms) {
                            $sms = new Sms();
                            $sms->send_sms($delivery->phone, 'new_order', $subSalesHeader);
                        }

                        if (isset($delivery->orders) && count($delivery->orders) > 0) {
                            $grand_gross = 0;
                            $grand_tax = 0;
                            foreach ($delivery->orders as $order) {

                                $product = $order->product;
                                $gross_amount = ((float)$product->price + ($order->paella ? $product->paella_price : 0)) * $order->qty;
                                $tax_amount = $gross_amount - ($gross_amount/1.12);
                                $grand_gross += $gross_amount;
                                $grand_tax += $tax_amount;

                                SalesDetail::create([
                                    'sales_header_id' => $subSalesHeader->id,
                                    'product_id' => $product->id,
                                    'product_name' => $product->name . ($order->paella ? ' Boneless with Paella' : ''),
                                    'product_category' => $product->category_id,
                                    'price' => $product->price,
                                    'cost' => 0,
                                    'tax_amount' => $tax_amount,
                                    'promo_id' => 0,
                                    'promo_description' => '',
                                    'discount_amount' => 0,
                                    'gross_amount' => $gross_amount,
                                    'net_amount' => $gross_amount,
                                    'qty' => $order->qty,
                                    'paella_qty' => $order->qty,
                                    'uom' => $product->uom,
                                    'size' => $product->size ?? "",
                                    'no_of_pax' => $product->no_of_pax ?? "",
                                    'paella_price' => $order->paella ? $product->paella_price : 0,
                                    'other_cost' => 0,
                                    'other_cost_description' => '',
                                    'created_by' => $user->id,
                                    'delivery_date' => $delivery->need_date . ' ' . $delivery->need_time,
                                    'has_baka' => $delivery?->isBaka ? 1 : 0,
                                    'lechon_baka_service' => $delivery?->lechon_baka_service ?? 0,
                                ]);

                                if ($product->id == 178) {
                                    $product = Product::whereId(270)->first();
                                    $gross_amount = ((float)$product->price) * $order->qty;
                                    $tax_amount = $gross_amount - ($gross_amount/1.12);
                                    $grand_gross += $gross_amount;
                                    $grand_tax += $tax_amount;
                                    SalesDetail::create([
                                        'sales_header_id' => $subSalesHeader->id,
                                        'product_id' => 270,
                                        'product_name' => $product->name,
                                        'product_category' => $product->category_id,
                                        'price' => $product->price,
                                        'cost' => 0,
                                        'tax_amount' => $tax_amount,
                                        'promo_id' => 0,
                                        'promo_description' => '',
                                        'discount_amount' => 0,
                                        'gross_amount' => $gross_amount,
                                        'net_amount' => $gross_amount,
                                        'qty' => $order->qty,
                                        'paella_qty' => 0,
                                        'uom' => $product->uom,
                                        'size' => $product->size ?? "",
                                        'no_of_pax' => $product->no_of_pax ?? "",
                                        'paella_price' => 0,
                                        'other_cost' => 0,
                                        'other_cost_description' => '',
                                        'created_by' => $user->id,
                                        'delivery_date' => $delivery->need_date . ' ' . $delivery->need_time,
                                        'has_baka' => $delivery?->isBaka ? 1 : 0,
                                        'lechon_baka_service' => $delivery?->lechon_baka_service ?? 0,
                                    ]);
                                }
                            }

                            SalesHeader::where('id', $subSalesHeader->id)->update([
                                'gross_amount' => $grand_gross + ($delivery?->lechon_baka_service ?? 0),
                                'tax_amount' => $grand_tax,
                                'net_amount' => $grand_gross + $delivery->delivery_fee + ($delivery?->lechon_baka_service ?? 0) - $discount,
                            ]);
                        }
                    }
                }
            }
        }

        $grand_gross = 0;
        $grand_tax = 0;

        $coupon_code = 0;
        $coupon_amount = 0;
        $saved_items = '';
//        $carts = Cart::where('user_id',$user->id)->get();
        // convert to collection above
        $carts = collect($carts);
        foreach ($carts as $cart) {
            if(!empty($cart->coupon_code)){
                
                $ccode = explode("|", $cart->coupon_code);
                foreach($ccode as $cd){
                    $code = explode(":",$cd);

                    $coupon = $this->use_coupon($code[0],$salesHeader->id);

                    if(!empty($coupon)){

                        SalesPayment::create([
                            'sales_header_id' => $salesHeader->id,
                            'payment_type' => 'Gift Cert',
                            'amount' => $code[1],
                            'status' => $salesHeader->payment_status == 'PAID' ? 'PAID' : 'PENDING',
                            'payment_date' => date('Y-m-d'),
                            'receipt_number' => $code[0],
                            'created_by' => Auth::id()
                        ]);
                    }
                }
            }
            
            $product = $cart->product;
            $gross_amount = ((float)$product->price + ($cart->paella_price > 0 ? $product->paella_price : 0)) * $cart->qty;
            $tax_amount = $gross_amount - ($gross_amount/1.12);
            $grand_gross += $gross_amount;
            $grand_tax += $tax_amount;
            
            $couponsList = json_decode($request->coupons, true);
            if ($couponsList && $request->discount_amount) {
                if (count($couponsList) > 0) {
                    foreach ($couponsList as $coupon) {
                        $couponCode = Coupon::whereRaw('LOWER(coupon_code) = ?', [strtolower($coupon['code'])])
                            ->where('status', 'ACTIVE')
                            ->first();

                        if ($couponCode) {
                            CouponSale::create([
                                'customer_id' => $user->id,
                                'coupon_id' => $couponCode?->id,
                                'coupon_code' => $couponCode->coupon_code,
                                'product_id' => $product->id,
                                'sales_header_id' => $salesHeader->id,
                                'discount_used' => $coupon['discount_used']
                            ]);
                        }
                    }
                }
            }

            $data['price'] = $product->price;
            $data['tax'] = $data['price'] - ($data['price']/1.12);
            $data['other_cost'] = 0;
            $data['net_price'] = $data['price'] - ($data['tax'] + $data['other_cost']);

            $withBaka = false;

            if ($product->id == 178) {
                $withBaka = true;
            }

            $bakaQty = 0;

            if ($withBaka) {
                $bakaQty = $cart->qty;
            }
           
            SalesDetail::create([
                'sales_header_id' => $salesHeader->id,
                'product_id' => $product->id,
                'product_name' => $product->name . ($cart->paella_price > 0 ? ' Boneless with Paella' : ''),
                'product_category' => $product->category_id,
                'price' => $product->price,
                'cost' => 0,
                'tax_amount' => $tax_amount,
                'promo_id' => 0,
                'promo_description' => '',
                'discount_amount' => 0,
                'gross_amount' => $gross_amount,
                'net_amount' => $gross_amount,
                'qty' => $cart->qty,
                'paella_qty' => $cart->qty,
                'uom' => $product->uom,
                'size' => $product->size ?? "",
                'no_of_pax' => $product->no_of_pax ?? "",
                'paella_price' => $cart->paella_price > 0 ? $product->paella_price : 0,
                'other_cost' => 0,
                'other_cost_description' => '',
                'created_by' => $user->id,
                'delivery_date' => $date_needed,
                'has_baka' => $withBaka ? 1 : 0,
                'lechon_baka_service' => $withBaka ? ($bakaQty * $bakaServiceFee) : 0,
            ]);
            $saved_items .= $cart->qty." x ".$product->name.", ";
        }

        // $salesDetails = SalesDetail::where('sales_header_id', $salesHeader->id)->get();

        // foreach ($salesDetails as $detail) {
        //     $subSales = SalesHeader::where('parent_sales_header_id', $salesHeader->id)->get();

        //     if ($subSales && count($subSales) > 0) {
        //         foreach ($subSales as $sub) {
        //             $detail->sales_header_id = $sub->id;
        //             $detail->save();
        //         }
        //     }
        // }

        $recipient = $user->valid_email ?? $user->email ?? $request->email;
        $salesHeader = SalesHeader::with(['couponUsed', 'deliveryAddress'])->find($salesHeader->id);
        if (auth()->guest()) {
            try {
                Mail::to($recipient)->send(new SalesCompleted($salesHeader));
            } catch (\Exception $th) {
                //throw $th;
            }
            $carted = array();
            session(['cart' => $carted]);
        } else{
            try {
                Mail::to($recipient)->send(new SalesCompletedRegistered($salesHeader)); 
            } catch (\Exception $th) {
                //throw $th;
            }
            Cart::where('user_id', $user->id)->delete();
        }
        try {
            Mail::to(config('app.email'))->send(new SalesCompletedAdmin($salesHeader));
        } catch (\Exception $th) {
            //throw $th;
        }
        $email_to_branch = $this->email_to_branch($salesHeader);

        if(strlen($salesHeader->customer_contact_number) > 1){
            $sms = new Sms();
            $sms->send_sms($salesHeader->customer_contact_number, 'new_order', $salesHeader);
        }

        $multipleDeliveries = ProductDeliveryAddress::where('sales_header_id', $salesHeader->id)->get();

        foreach ($multipleDeliveries as $delivery) {
            $sms = new Sms();
            if ($delivery?->receive_sms == 1 && strlen($delivery->contact_tel) > 1) {
                $sms->send_sms($delivery->contact_tel, 'new_order_delivery', $salesHeader);
            }
        }

        $merchantkey = '2amqVf04H9';
        $merchantcode = 'PH00125';
        $refno = $salesHeader->order_number;
        $amount = str_replace(".", "", number_format($deposit,2,'.',''));
        $currency = strtoupper($salesHeader->currency);

        $sign = $this->generateSignature($merchantkey,$merchantcode,$refno,$amount,$currency);

        $oldId = null;

        // if (session()->has('old_sales_header_id')) {
        //     $oldId = session('old_sales_header_id');
        // }

        // if ($oldId != null) {
        //     $oldSalesHeader = SalesHeader::where('id', $oldId)->first();

        //     if ($oldSalesHeader) {
        //         $oldSalesHeader->delete();
        //     }

        //     session()->forget('old_sales_header_id');
        // }
        
        session()->forget('edit_sales_header');

        //$payment = $this->ipay($salesHeader,$deposit,$sign);

        logger('sales_header_id', [$salesHeader->id]);
        logger('order_number', [$salesHeader->order_number]);
        logger('customer_contact_number', [$salesHeader->customer_contact_number]);
        logger('amount', [$deposit]);
        logger('signature', [$sign]);
        logger('saved_items', [$saved_items]);

        return response()->json([
                'success' => true,
                'sales_header_id' => $salesHeader->id,
                'order_number' => $salesHeader->order_number,
                'customer_contact_number' => $salesHeader->customer_contact_number,
                'customer_name' => $salesHeader->customer_name,
                'amount' => number_format($deposit,2,'.',''),
                'signature' => $sign,
                'saved_items' => rtrim($saved_items,", ")
            ]);

        //return redirect()->route('product.front.show_forsale')->with('success', 'Your order was successfull');
    }

    public function email_to_branch($salesHeader){
        $branch = Branch::where('name',$salesHeader->outlet)->first();
        if(!empty($branch)){
            if(strlen($branch->email_address) > 2){
                try {
                    $email_act = Mail::to(env($branch->email_address))->send(new SalesCompletedAdmin($salesHeader));
                } catch (\Exception $th) {
                    //throw $th;
                }
            }
        }
        return true;
    }


    public function generate_payment(Request $request){
        $salesHeader = SalesHeader::where('order_number',$request->order)->first();
        $sign = $this->generateSignature('2amqVf04H9','PH00125',$request->order,str_replace(".", "", number_format($request->amount,2,'.','')),'PHP');
        $payment = $this->ipay($salesHeader,$request->amount,$sign);
        $sale_items = '';
        foreach($salesHeader->items as $i){
            $sale_items .= $i->qty." x ".$i->product_name.", ";
        }
        return response()->json([
                'success' => true,
                'order_number' => $request->order,
                'customer_contact_number' => Auth::user()->contact_mobile,
                'amount' => number_format($request->amount,2,'.',''),
                'signature' => $sign,
                'saved_items' => rtrim($sale_items,", ")
            ]);
    }

    public function generate_payment_guest(Request $request){

        $salesHeader = SalesHeader::whereId($request->order)->first();
      
        $sign = $this->generateSignature('2amqVf04H9','PH00125',$request->order,str_replace(".", "", number_format($request->amount,2,'.','')),'PHP');
        $payment = $this->ipay($salesHeader,$request->amount,$sign);
        $sale_items = '';
        foreach($salesHeader->items as $i){
            $sale_items .= $i->qty." x ".$i->product_name.", ";
        }
        return response()->json([
                'success' => true,
                'order_number' => $request->order,
                'customer_contact_number' => $salesHeader->customer_contact_number,
                'amount' => number_format($request->amount,2,'.',''),
                'signature' => $sign,
                'saved_items' => rtrim($sale_items,", ")
            ]);
    }


    public function ipay($salesHeader,$amount,$sign){
        if (auth()->guest()) {
            $user = User::find(9999);
            if (empty($user)) {
                $user = $this->create_guest_account();
            }
        } else {
            $user = auth()->user();
        }

        
        
            $save_payment = SalesPayment::create([
                'sales_header_id' => $salesHeader->id,
                'payment_type' => 'IPAY',
                'payment_date' => date('Y-m-d'),
                'amount' => $amount,
                'status' => 'PENDING',
                'created_by' => $user->id,
                'signature' => $sign,
                'order_number' => $salesHeader->order_number
            ]);

        return;
    }

    public function complete_payment(){
        $datestart = date('Y-m-d H:i:s', strtotime('-10 minutes'));
        $dateend = date('Y-m-d H:i:s', strtotime('+1 minutes'));
        $save_payment = SalesPayment::where('order_number',$_GET['RefNo'])->whereBetween('created_at',[$datestart, $dateend])
            ->update([
            'payment_date' => date('Y-m-d'),
            'remark' => $_GET['Remark'],
            'status' => 'PAID',
            'trans_id' => $_GET['TransId'],
            'err_desc' => $_GET['ErrDesc'],
            'cc_name' => $_GET['cc_name'],
            'cc_no' => $_GET['cc_no'],
            'bank_name' => $_GET['bank_name'],
            'country' => $_GET['country']
        ]);

        return Redirect::to(env('APP_URL')."/order?order_completed=go");
    }

    public function cancel_payment(){
        $datestart = date('Y-m-d H:i:s', strtotime('-10 minutes'));
        $dateend = date('Y-m-d H:i:s', strtotime('+1 minutes'));
        $save_payment = SalesPayment::where('order_number',$_GET['RefNo'])->whereBetween('created_at',[$datestart, $dateend])
            ->update([
            'payment_date' => date('Y-m-d'),
            'remark' => $_GET['Remark'],
            'status' => 'CANCELLED',
            'trans_id' => $_GET['TransId'],
            'err_desc' => $_GET['ErrDesc']
        ]);
        
        return Redirect::to(route('order-history')."?order_cancelled=1&order_no=".$_GET['RefNo']);
    }

    public function generateSignature()
    {
        $stringToHash = implode('',func_get_args());
        return base64_encode(self::_hex2bin(sha1($stringToHash)));
    }


    private function _hex2bin($source)
    {
        $bin = null;
        for ($i=0; $i < strlen($source); $i=$i+2) {
            $bin .= chr(hexdec(substr($source, $i, 2)));
        }
        return $bin;
    }

    public function use_coupon($code,$sales_id){

        $coupon = GiftCertificate::whereCode($code)->whereStatus('Unused')->first();
    
        if(empty($coupon)){
            return false;
        }

        $use_coupon = $coupon->update([
            'status' => 'Used',
            'sales_header_id' => $sales_id
        ]);

        return $coupon;
    }

    public function apply_coupon(Request $request){

        $coupon = GiftCertificate::whereCode($request->coupon)->whereStatus('Unused')->first();
        if(empty($coupon)){
            return back()->with('error','Invalid coupon code!');
        }

        $verify = $this->verifyCouponFromCart($request->coupon);
        if($verify == 1){
            return back()->with('error','Coupon code already used!');
        }

        $total_coupon_discount = 0;
       
        $carts = Cart::where('user_id',Auth::id())->orderBy('coupon_code', 'DESC')->first();
        

        if(empty($carts->coupon_code)){

            $coupons = $request->coupon.":".$coupon->amount;
            $total_coupon_discount = $coupon->amount;

        }else{

            $coupons = $carts->coupon_code."|".$request->coupon.":".$coupon->amount;
            $total_coupon_discount = $this->getCouponTotalAmount($coupons);

        }
        if (auth()->guest()) {            
            session(['coupon_code' => $coupons]); 
            session(['coupon_amount' => $total_coupon_discount]); 
        }
        else{
            $apply_coupon = $carts->update([
                'coupon_code' => $coupons,
                'coupon_amount' => $total_coupon_discount
            ]);
        }

        return back()->with('coupon-success','Successfully applied coupon');
    }

    public function getCouponTotalAmount($data){

        $coupons = explode("|", $data);
        $total = 0;
        foreach($coupons as $coupon){
            if(strlen($coupon)>1){
                $c = explode(":", $coupon);
                $total += $c[1];
            }
        }

        return $total;
    }

    public function verifyCouponFromCart($code){
        $carts = Cart::whereNotNull('coupon_code')->get();
        $rs = 0;
        foreach($carts as $cart){

            $coupons = explode("|", $cart);

            foreach($coupons as $coupon){
                if(strlen($coupon)>1){
                    $c = explode(":", $coupon);
                    if($c[0]==$code){
                        return 1;
                    }
                }
            }

        }

        return 0;
    }

    public function deapply_coupon(Request $request){

        $new_code = '';
        $new_amount = 0;
        $cart = Cart::where('user_id',Auth::id())->whereNotNull('coupon_code')->first();
        $coupons = explode("|", $cart->coupon_code);

        foreach($coupons as $coupon){
            if(strlen($coupon)>1){
                $c = explode(":", $coupon);
                if($c[0]!=$request->coupon_delete){
                    $new_code .= $coupon."|";
                }
            }
        }
        $new_code = rtrim($new_code,"|");
        $new_amount = $this->getCouponTotalAmount($new_code);

        $update = Cart::whereId($cart->id)->update([
            'coupon_code' => $new_code,
            'coupon_amount' => $new_amount
        ]);

        return back()->with('coupon-remove-success','Successfully removed coupon');
    }

    public function create_guest_account()
    {
        $guestAccount = [
            9999,
            'Guest',
            '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'guest',
            'Guest',
            'wsiphproduction@gmail.com',
            'web',
            Hash::make(Str::random(10)),
            1
        ];

        DB::insert('insert into users (id, name, password, registration_type, firstname, email, registration_source, remember_token, is_active) values (?, ?, ?, ?, ?, ?, ?, ?)', $guestAccount);

        return User::find(9999);
    }

    public function get_shipping_fee_for_multiple_address_new(Request $request)
    {
        $locations = $request->input('locations');
        $productIds = $request->input('products', []);
        $totalFee = 0;
        $fees = [];

        // products request is now in this format
        // [
        //     {
        //         "product_id": 178,
        //         "qty": 2
        //     },
        //     {
        //         "product_id": 170,
        //         "qty": 1
        //     }
        // ]

        $carts = collect([]);

        foreach ($productIds as $product) {
            $p = Product::find($product['product_id']);
            if (!$p) continue;

            $carts->push((object)[
                'product_id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'paella_price' => $p->paella_price ?? 0,
                'qty' => $product['qty'],
                'photo' => $p->photos()->first() ? asset('storage/products/' . $p->photos()->first()->path) : '',
                'product' => $p,
                'qty' => $product['qty'],
            ]);
        }

        // $carts = Product::whereIn('id', $productIds)
        //     ->get()
        //     ->map(function ($product) {
        //         return (object)[
        //             'product_id' => $product->id,
        //             'name' => $product->name,
        //             'price' => $product->price,
        //             'paella_price' => $product->paella_price ?? 0,
        //             'qty' => 1,
        //             'photo' => $product->photos()->first() ? asset('storage/products/' . $product->photos()->first()->path) : '',
        //             'product' => $product
        //         ];
        //     });

        $check_customer = Auth::check() && \App\Models\DeliveryFeePromo::check_customer(Auth::id()) ? 1 : 0;

        // Handle single location
        if (is_string($locations)) {
            $fee = $this->calculateRate($locations, $carts, $check_customer);
            $fees[] = [
                'location' => $locations['city'] . ', ' . $locations['province'],
                'fee' => $fee['rate'],
                'is_baka' => $fee['is_baka'],
                'lechon_baka_service' => $fee['lechon_baka_service']
            ];
            $totalFee = $fee['rate'];
        }

        // Handle multiple locations
        if (is_array($locations)) {
            foreach ($locations as $loc) {
                $fee = $this->calculateRate($loc, $carts, $check_customer);
                $fees[] = [
                    'location' => $loc['city'] . ', ' . $loc['province'],
                    'fee' => $fee['rate'],
                    'is_baka' => $fee['is_baka'],
                    'lechon_baka_service' => $fee['lechon_baka_service']
                ];
                $totalFee += $fee['rate'];
            }
        }

        return response()->json([
            'fees' => $fees,
            'fee' => $totalFee,
            'has_baka' => collect($fees)->contains('is_baka', true),
            'lechon_baka_service_total' => collect($fees)->where('is_baka', true)->sum('lechon_baka_service')
        ]);
    }


    public function get_shipping_fee_for_multiple_address(Request $request)
    {
        $locations = $request->input('locations');
        $rate = 0;
        $totalFee = 0;

        $carts = Auth::check()
            ? Cart::where('user_id', Auth::id())->get()
            : collect(session('cart', []));

        $check_customer = Auth::check() && \App\Models\DeliveryFeePromo::check_customer(Auth::id()) ? 1 : 0;

        // Handle single location (string)
        if (is_string($locations)) {
            $totalFee = $this->calculateRate($locations, $carts, $check_customer);
        }

        // Handle multiple locations (array)
        if (is_array($locations)) {
            foreach ($locations as $loc) {
                $totalFee += $this->calculateRate($loc, $carts, $check_customer);
            }
        }

        return response()->json([
            'fee' => $totalFee
        ]);
    }

    private function calculateRate($location, $carts, $check_customer)
    {
        $rate = 0;
        $baka = 0;
        $check_product = 0;

        $location_lechon = Deliverablecities::where('is_active', 1)->where('city', $location['city'])->where('province', $location['province'])->where('item_type', 'lechon')->first();
        $location_misc = Deliverablecities::where('is_active', 1)->where('city', $location['city'])->where('province', $location['province'])->where('item_type', 'misc')->first();

        if (!empty($location_misc)) {
            $rate = $location_misc->rate;
        }

        $bakaQty = 0;

        foreach ($carts as $cart) {
            $delivery_promo = \App\Models\DeliveryFeePromo::check_product($cart->product_id);
            if ($delivery_promo == 1) {
                $check_product = 1;
            }

            $p = Product::find($cart->product_id);
            if (!$p) continue;

            if ($p->is_misc == 0) {
                $rate = $location_lechon?->rate ?? 0;
            }

            if ($p->id == 178) { // lechon baka
                $baka = 1;
                $bakaQty += $cart->qty;
            }
        }

        if ($baka == 1 && $location_lechon?->outside_manila == 0) {
            $rate = 0;
        }

        if ($check_product == 1 || $check_customer == 1) {
            $rate = 0;
        }

        return [
            'rate' => $rate,
            'is_baka' => $baka == 1 ? true : false,
            'lechon_baka_service' => $baka == 1 ? floatval(Product::whereId(270)->first()->price * $bakaQty) : 0
        ];
    }

    public function get_shipping_fee(Request $request){
        $province = $request->province;
        $city = $request->city;

        if (!$province || !$city) {
            return response()->json([
                'fee' => 0,
                'location' => $request->city .', '.$request->province
            ]);
        }

        $rate=0;
        $baka = 0;
        $check_product = 0;
        $check_customer = 0;
        //$baka_with_fee = ['Imus Cavite','Molino'];
        $location_lechon = Deliverablecities::where('is_active', 1)->where('province', $province)->where('city', $city)->where('item_type','lechon')->first();
        $location_misc = Deliverablecities::where('is_active', 1)->where('province', $province)->where('city', $city)->where('item_type','misc')->first();

        if (Auth::user()) {
            $carts = Cart::where('user_id',Auth::id())->get();
            $check_customer_promo = \App\Models\DeliveryFeePromo::check_customer(Auth::id());
            if($check_customer_promo == 1){
                $check_customer = 1;
            }

        } else {
            $carts = collect(session('cart', []));          
        }

        if(!empty($location_misc)){
            $rate = $location_misc->rate;
        }  
        $bakaQty = 0;      
        foreach($carts as $cart){
            
            $delivery_promo = \App\Models\DeliveryFeePromo::check_product($cart->product_id);
            if($delivery_promo == 1){
                $check_product = 1;
            }

            $p = Product::whereId($cart->product_id)->first();
            if($p->is_misc == 0){
                if(!$location_lechon)
                    $rate = 0;
                else
                    $rate = $location_lechon->rate;
            }
            if($p->id == 178 ) // if lechon baka
            {
                $baka = 1;
                $bakaQty += $cart->qty;
            }
        }

        if(!isset($rate)){
            $rate = 0 ;
        }

        if($check_product == 1 || $check_customer == 1){
            $rate = 0;
        }

        if ($baka == 1 && $location_lechon->outside_manila == 0) {
            $rate = 0;
        }

        $bakaServicePrice = 0;

        if ($baka == 1) {
            $bakaServicePrice = Product::whereId(270)->first()->price * $bakaQty;
        }

        if ($request->has('force_fee')) {
            return $rate;
        } else {
            return response()->json([
                'fee' => $rate,
                'location' => $request->city .', '.$request->province,
                'is_baka' => $baka == 1 ? true : false,
                'lechon_baka_service' => floatval($bakaServicePrice)
            ]);
        }
    }  

    public function cartCount(Request $request)
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', Auth::id())->get();

            $carts = $cart->reject(function ($item) {
                return data_get($item, 'product_id') == 270;
            });

            return response()->json([
                'totalItems' => $carts->count()
            ]);
        } else {
            $cart = session('cart', []);

            $carts = collect($cart)->reject(function ($item) {
                return data_get($item, 'product_id') == 270;
            });

            session(['cart' => $carts->values()->all()]);

            return response()->json([
                'totalItems' => $carts->count()
            ]);
        }
    }

    public function getCart(Request $request)
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', Auth::id())
                        ->with('product.photos')
                        ->get();
        } else {
            // Normalize session cart to collection
            $cart = collect(session('cart', []));
        }

        // Works for both session arrays AND model objects
        $carts = $cart->reject(function ($item) {
            return data_get($item, 'product_id') == 270;
        });

        if (!auth()->check()) {
            $carts = $carts->values()->all();
        }

        return response()->json([
            'cart' => $carts
        ]);
    }


    public function removeCart(Request $request)
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_remove_id)
                ->where('paella', $request->paella == 1)
                ->first();

                
            if ($cart) {
                Cart::where('product_id', $request->product_remove_id)
                    ->where('user_id', Auth::id())
                    ->where('paella', $request->paella == 1)
                    ->delete();
            }

            if ($request->product_remove_id == 178) {
                Cart::where('product_id', 270)
                    ->where('user_id', Auth::id())
                    ->delete();
            }
        } else {
            $cart = session('cart', []);
            $productId = (int) $request->product_remove_id;
            $paella = (int) $request->paella;
    
            // Filter out the Cart objects by checking product_id directly
            $filtered = array_values(array_filter($cart, function ($item) use ($productId, $paella) {
                return (int) $item['product_id'] !== $productId || (int) $item['paella'] !== $paella;
            }));

            if ($productId == 178) {
                $filtered = array_values(array_filter($filtered, function ($item) {
                    return (int) $item['product_id'] !== 270;
                }));
            }
    
            session(['cart' => $filtered]);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart'
        ]);
    }

    public function generateUniqueEmail($baseEmail) {
        $emailParts = explode('@', $baseEmail);
        $localPart = $emailParts[0];
        $domainPart = $emailParts[1];
        $counter = 1;

        while (User::where('email', $baseEmail)->exists()) {
            $baseEmail = $localPart . '-' . $counter . '@' . $domainPart;
            $counter++;
        }

        return $baseEmail;
    }
}
