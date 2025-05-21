<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Cart;
use App\Mail\SalesCompleted;
use App\Mail\SalesCompletedAdmin;
use App\Mail\SalesCompletedRegistered;
use Illuminate\Support\Facades\Mail;
use App\EcommerceModel\SalesPayment;
use App\EcommerceModel\Branch;
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
use App\Models\ProductDeliveryAddress;
use Redirect;
use DateTime;

use Carbon\Carbon;
use Cookie;
use Illuminate\Support\Str;


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
        $paella_cost = ($request->ac_paella == '1' ? ($product->paella_price * $request->ac_qty) : 0);
        if (auth()->check()) {

            $cart = Cart::where('product_id', $request->ac_item)
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {
                $newQty = $request->ac_qty;

                Cart::where('product_id', $request->ac_item)
                    ->where('user_id', Auth::id())
                    ->update([
                        'qty' => $newQty,
                        'price' => $product->price,
                        'paella_price' => $paella_cost
                    ]);
            } else {
                Cart::create([
                    'product_id' => $request->ac_item,
                    'user_id' => Auth::id(),
                    'qty' => $request->ac_qty,
                    'price' => $mainProduct->price,
                    'paella_price' => $paella_cost,
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
                if ($order->product_id == $request->ac_item) {
                    $cart[$key]->qty = $request->ac_qty;
                    $cart[$key]->price = $product->price;
                    $cart[$key]->paella_price = $paella_cost;
                    $cart[$key]->photo = $photo;
                    $cart[$key]->product = $product;

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

    public function store(Request $request)
    {
        $product = Product::with('photos')->whereId($request->ac_item)->first();
        $photos = $product->photos()->where('is_primary', 1)->first();
        $photo = !empty($photos) ? asset('storage/products/'.$photos->path ) : '';
        $paella_cost = ($request->ac_paella == '1' ? ($product->paella_price * $request->ac_qty) : 0);
        if (auth()->check()) {

            $cart = Cart::where('product_id', $request->ac_item)->with('product')
                ->where('user_id', Auth::id())
                ->first();

            if (!empty($cart)) {
                $newQty = $cart->qty + $request->ac_qty;
                $save = $cart->update([
                    'qty' => $newQty,
                    'price' => $product->price,
                    'paella_price' => $paella_cost
                ]);
            } else {
                $save = Cart::create([
                    'product_id' => $request->ac_item,
                    'user_id' => Auth::id(),
                    'qty' => $request->ac_qty,
                    'price' => $product->price,
                    'paella_price' => $paella_cost,
                    'photo' => $photo,
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
                        logger($miscQty);
                        $save = $cart->update([
                            'qty' => $miscQty,
                            'price' => $prod->price
                        ]);
                    } else {
                        logger('11111');
                        $save = Cart::create([
                            'product_id' => $miscProductId,
                            'user_id' => Auth::id(),
                            'qty' => $miscQty,
                            'price' => $prod->price,
                            'photo' => $image,
                            'paella_price' => 0
                        ]);
                    }
                }
            }
        } else {
            $cart = session('cart', []);
            $not_exist = true;

            foreach ($cart as $key => $order) {
                if ($order->product_id == $request->ac_item) {
                    $cart[$key]->qty = $request->ac_qty;
                    $cart[$key]->price = $product->price;
                    $cart[$key]->paella_price = $paella_cost;
                    $cart[$key]->photo = $photo;
                    $cart[$key]->product = $product;

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
                            $cart[$key]->qty = $miscQty;
                            $cart[$key]->price = $prod->price;
                            $cart[$key]->paella_price = 0;
                            $cart[$key]->photo = $image;
                            $cart[$key]->product = $prod;
                            $miscExist = true;
                            break;
                        }
                    }
            
                    if (!$miscExist) {
                        $order = new Cart();
                        $order->product_id = $miscProductId;
                        $order->qty = $miscQty;
                        $order->price = $prod->price;
                        $order->paella_price = 0;
                        $order->photo = $image;
                        $order->product = $prod;
            
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

            return redirect()->route('cart.front.checkout');
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

    public function save_sales(Request $request) {

        // dd($request->all());
        if (auth()->guest()) {
            $user = User::find(9999);
            if (empty($user)) {
                $user = $this->create_guest_account();
            }
            $customer_name = $request->name;
            $user->contact_mobile = $request->mobile;
            $user->email = $request->email;
            $user->contact_mobile = $request->mobile;
            $carts = collect(session('cart', []));
        } else {
            $user = auth()->user();
            $customer_name = $user->name;
            $carts = Cart::where('user_id',$user->id)->get();
        }

        //dd($request);
        $dn = explode(" - ", $request->need_date . ' - ' . $request->need_time);
        $date_needed = date('Y-m-d H:i:s',strtotime($dn[0]." ".$dn[1]));
        $deposit = $request->deposit;
        if($request->shipping_type == 'pickup'){
            $delivery_type='Store Pickup';
            $outlet = $request->delivery_branch;
            $customer_delivery_adress = $request?->delivery_branch ?? $request->delivery_address;            
            $customer_contact_number = $user->contact_mobile;
            $customer_location = '';
            $contact_person = $customer_name;
        }
        else{
            $delivery_type='Door to door delivery';
            if($request->location == 'Other'){
                $customer_delivery_adress = $request->delivery_address;  
            }
            else{
                $customer_delivery_adress = $request->delivery_address.", ".$request->location;  
            }
                     
            $customer_contact_number = $request->mobile;
            $customer_location = $request->location;
            $contact_person = $request->name;
            $outlet = '';
        }
        $totalPrice = $request->total_amount;
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
        
        $salesHeader = SalesHeader::create([
            'user_id' => $user->id,
            'email' => $user->email ?? $request->email,
            'order_number' => $requestId,
            'customer_name' => $customer_name,
            'customer_contact_number' => $customer_contact_number,
            'customer_address' => $customer_delivery_adress,
            'customer_delivery_adress' => $customer_delivery_adress,
            'delivery_tracking_number' => '',
            'delivery_type' => $delivery_type,
            'delivery_fee_amount' => $request->delivery_fee,
            'order_source' => 'Web',
            'gross_amount' => $totalPrice,
            'tax_amount' => 0,
            'net_amount' => $totalPrice,
            'discount_amount' => 0,
            'payment_status' => 'UNPAID',
            'delivery_status' => '',
            'status' => 'active',
            'currency' => 'PHP',
            'customer_location' => $customer_location,
            'instruction' => $request->instruction,
            'agent' => $request->agent,
            'contact_person' => $contact_person,
            'outlet' => $outlet,
            'origin' => $origin,
            'forecast_date' => $forecast_date
        ]);

        $salesHeader->update([
            'order_number' => sprintf('%07d', $salesHeader->id)
        ]);

        $salesHeader->refresh();
        
        if ($request->has('deliveries')) {
            $deliveries = json_decode($request->deliveries ?? '');
            if ($deliveries && count($deliveries) > 0) {
                foreach ($deliveries as $key => $delivery) {
                    if ($delivery?->order?->product_id) {
                        $single_address = $delivery->address;
                        $single_name = $delivery->name;
                        $single_phone = $delivery->phone;
                        $single_qty = $delivery->qty;
                        $single_order = $delivery->order->product_id;
                        $single_location = $delivery->location;
                        $single_delivery_fee = $delivery->delivery_fee;
                        $single_date = $delivery->need_date;
                        $single_time = $delivery->need_time;
                        $single_note = $delivery->note;
    
                        ProductDeliveryAddress::create([
                            'sales_header_id' => $salesHeader->id,
                            'address' => $single_address,
                            'name' => $single_name,
                            'phone' => $single_phone,
                            'qty' => $single_qty,
                            'order' => $single_order,
                            'location' => $single_location,
                            'delivery_fee' => $single_delivery_fee,
                            'delivery_date' => $single_date,
                            'delivery_time' => $single_time,
                            'note' => $single_note,
                            'product_id' => $single_order,
                            'branch' => $request->delivery_branch,
                        ]);
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
        foreach ($carts as $cart) {
            if(!empty($cart->coupon_code)){
                
                $ccode = explode("|", $cart->coupon_code);
                foreach($ccode as $cd){
                    $code = explode(":",$cd);

                    $coupon = $this->use_coupon($code[0],$salesHeader->id);

                    if(!empty($coupon)){

                        $payment_coupon = SalesPayment::create([
                            'sales_header_id' => $salesHeader->id,
                            'payment_type' => 'Gift Cert',
                            'amount' => $code[1],
                            'status' => 'PENDING',
                            'payment_date' => date('Y-m-d'),
                            'receipt_number' => $code[0],
                            'created_by' => Auth::id()
                        ]);
                    }
                }
            }
            
            $product = $cart->product;
            $gross_amount = ($product->price * $cart->qty) + ($cart->paella_price * $cart->qty);
            $tax_amount = $gross_amount - ($gross_amount/1.12);
            $grand_gross += $gross_amount;
            $grand_tax += $tax_amount;


            $data['price'] = $product->price;
            $data['tax'] = $data['price'] - ($data['price']/1.12);
            $data['other_cost'] = 0;
            $data['net_price'] = $data['price'] - ($data['tax'] + $data['other_cost']);
           
            SalesDetail::create([
                'sales_header_id' => $salesHeader->id,
                'product_id' => $product->id,
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
                'qty' => $cart->qty,
                'paella_qty' => $cart->qty,
                'uom' => $product->uom,
                'size' => $product->size ?? "",
                'no_of_pax' => $product->no_of_pax ?? "",
                'paella_price' => $cart->paella_price,
                'other_cost' => 0,
                'other_cost_description' => '',
                'created_by' => $user->id,
                'delivery_date' => $date_needed
            ]);
            $saved_items .= $cart->qty." x ".$product->name.", ";
            
            // if($coupon_amount > 0){
            //     $grand_gross = $grand_gross - $coupon_amount;
            //     $update_header = SalesHeader::whereId($salesHeader->id)->update([
            //         'gross_amount' => $grand_gross,
            //         'tax_amount' => $grand_tax,
            //         'net_amount' => $grand_gross,
            //         'discount_amount' => $coupon_amount
            //     ]);
            // }
        }

        $recipient = $user->email ?: $request->email;
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
            // $sms->send_sms($salesHeader->customer_contact_number, 'new_order', $salesHeader);
        }

        $merchantkey = '2amqVf04H9';
        $merchantcode = 'PH00125';
        $refno = $salesHeader->order_number;
        $amount = str_replace(".", "", number_format($deposit,2,'.',''));
        $currency = strtoupper($salesHeader->currency);

        $sign = $this->generateSignature($merchantkey,$merchantcode,$refno,$amount,$currency);

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

        return Redirect::to(env('APP_URL')."/account/sales?order_cancelled=1&order_no=".$_GET['RefNo']);
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
            'Guest',
            'wsiphproduction@gmail.com',
            'web',
            STR::random(10),
            1
        ];

        DB::insert('insert into users (id, name, password, firstname, email, registration_source, remember_token, is_active) values (?, ?, ?, ?, ?, ?, ?, ?)', $guestAccount);

        return User::find(9999);
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

        $location_lechon = Deliverablecities::whereName($location)->where('item_type', 'lechon')->first();
        $location_misc = Deliverablecities::whereName($location)->where('item_type', 'misc')->first();

        if (!empty($location_misc)) {
            $rate = $location_misc->rate;
        }

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

            if ($p->id == 42) { // lechon baka
                $baka = 1;
            }
        }

        if ($baka == 1) {
            $rate = 0;
        }

        if ($baka == 1 && $location_lechon && $location_lechon->outside_manila == 1) {
            $rate = 3000;
        }

        if ($check_product == 1 || $check_customer == 1) {
            $rate = 0;
        }

        return $rate;
    }

    public function get_shipping_fee(Request $request){

        $rate=0;
        $baka = 0;
        $check_product = 0;
        $check_customer = 0;
        //$baka_with_fee = ['Imus Cavite','Molino'];
        $location_lechon = Deliverablecities::whereName($request->location)->where('item_type','lechon')->first();
        $location_misc = Deliverablecities::whereName($request->location)->where('item_type','misc')->first();
        
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
            if($p->id == 42 ) // if lechon baka
            {
                $baka = 1;
            }
        }
        if(!isset($rate)){
            $rate = 0 ;
        }

        if($baka == 1){
            $rate = 0;
        }
        if($baka == 1 && $location_lechon->outside_manila == 1){
            $rate = 3000;
        }

        if($check_product == 1 || $check_customer == 1){
            $rate = 0;
        }
        
        return response()->json([
            'fee' => $rate
        ]);
    }  

    public function cartCount(Request $request)
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', Auth::id())->get();

            return response()->json([
                'totalItems' => $cart->count()
            ]);
        } else {
            $cart = session('cart', []);

            return response()->json([
                'totalItems' => count(session('cart', []))
            ]);
        }
    }

    public function getCart(Request $request)
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', Auth::id())->with('product.photos')->get();
        } else {
            $cart = session('cart', []);
        }

        return response()->json([
            'cart' => $cart
        ]);
    }

    public function removeCart(Request $request)
    {
        if (auth()->check()) {
            $cart = Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_remove_id)
                ->first();
                
            if ($cart) {
                Cart::where('product_id', $request->product_remove_id)
                    ->where('user_id', Auth::id())
                    ->delete();
            }
        } else {
            $cart = session('cart', []);
            $productId = (int) $request->product_remove_id;
    
            // Filter out the Cart objects by checking product_id directly
            $filtered = array_values(array_filter($cart, function ($item) use ($productId) {
                return (int) $item->product_id !== $productId;
            }));
    
            session(['cart' => $filtered]);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart'
        ]);
    }
}
