<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Branch;
use App\EcommerceModel\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;


use App\EcommerceModel\Coupon;
use App\EcommerceModel\CouponCart;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use App\Models\Deliverablecities;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    private $searchFields = ['name'];
    public function __construct()
    {
        Permission::module_init($this, 'coupon');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $listing = new ListingHelper('desc', 10, 'updated_at');

    
    $query  = DB::table('coupon_new');


    $searchFields = ['code', 'status'];


    $coupons = $listing->simple_search_query(
        $query,    
        $searchFields
    );


    $filter = $listing->get_filter($searchFields);

    $searchType = 'simple_search_query';

    return view('admin.coupon.index', compact('coupons', 'filter', 'searchType'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('status','PUBLISHED')->get();
        $categories =  ProductCategory::has('published_products')->where('status','PUBLISHED')->get();
        //$brands = Product::whereNotNull('brand')->distinct()->get(['brand']);
        $customers = User::where('role_id',6)->where('is_active',1)->get();

        $locations = Deliverablecities::distinct()->where('is_active', 1)->orderBy('name')->get(['name']);
        $free_products = Product::get();

        return view('admin.coupon.create',compact('products','categories','customers','locations','free_products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function autoFreeDelivery(Request $request)
{
 $subtotal = (float) $request->subtotal;

$coupon = DB::table('coupon_new')
    ->where('discount_type', 'free delivery')
    ->where('is_auto_apply', 'Yes')
    ->where('status', 'active')
    ->where('min_spend', '<=', $subtotal)
    ->orderByDesc('min_spend')
    ->first();

if ($coupon) {
    session([
        'coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type
        ]
    ]);

    return response()->json([
        'applied' => true,
        'coupon' => $coupon 
    ]);
}

if (
    session()->has('coupon') &&
    session('coupon.discount_type') === 'free delivery'
) {
    session()->forget('coupon');
}

return response()->json(['applied' => false]);
}
    public function insert_coupons(Request $request)
{ 
    $request->validate([
        'coupon_name'    => 'required|unique:coupon_new,coupon_name',
        'coupon_desc'    => 'required',
        'coupon_code'    => 'required',
        'discount_type'  => 'required|in:percentage,fixed,free delivery',
        'auto_apply'  => 'required|in:Yes,No',
        'discount_value' => 'required|numeric',

        'min_spend'      => 'nullable|numeric',
        'usage_limit'    => 'nullable|integer',
        'region_name'    => 'nullable|string',
        'province_name'  => 'nullable|string',
        'city_name'      => 'nullable|string',
        'barangay_name'  => 'nullable|string',

        'start_date'     => 'required|date',
        'start_time'     => 'required',
        'end_date'       => 'required|date',
        'end_time'       => 'required',
        'status'         => 'required|boolean',
    ]);

    DB::table('coupon_new')->insert([
        'coupon_name'     => $request->coupon_name,
        'coupon_desc'     => $request->coupon_desc,
        'code'            => $request->coupon_code,
        'discount_type'   => $request->discount_type,
        'discount_value'  => $request->discount_value,
        'min_spend'       => $request->min_spend,
        'usage_limit'     => $request->usage_limit,
        'is_auto_apply'     => $request->auto_apply,
        'product_id'     => $request->product_id,
        'region_code'      => $request->region_code,  
        'province_code'   => $request->province_code,
        'city_code'   =>$request->city_code,
        'barangay_code'=>$request->barangay_code,
        'start_date'      => $request->start_date,
        'start_time'      => $request->start_time,
        'end_date'        => $request->end_date,
        'end_time'        => $request->end_time,
        'status'          => $request->status,

        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    return redirect()->back()->with('success', 'Coupon created successfully!');
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        $products = Product::where('status','PUBLISHED')->get();
        $categories =  ProductCategory::has('published_products')->where('status','PUBLISHED')->get();
      
        $customers = User::where('role_id',6)->where('is_active',1)->get();
        $locations = Deliverablecities::distinct()->where('is_active', 1)->orderBy('name')->get(['name']);
        $free_products = Product::get();

        $selectedCustomers = explode('|', $coupon->scope_customer_id ?? '');
        $selectedCustomers = array_filter($selectedCustomers, function($value) { return !is_null($value) && $value !== ''; });

        $selectedFreeProducts = explode('|', $coupon->free_product_id ?? '');
        $selectedFreeProducts = array_filter($selectedFreeProducts, function($value) { return !is_null($value) && $value !== ''; });

        return view('admin.coupon.edit',compact('coupon','products','categories','customers','locations','free_products', 'selectedCustomers', 'selectedFreeProducts'));
    }

     public function edit_coupon(Request $request, $id)
    {
        $coupon = DB::table('coupon_new')->where('id', $id)->first();

       return view('admin.coupon.edit', compact('coupon'));
    }
    public function update_coupon(Request $request, $id)
{

    $validator = Validator::make($request->all(), [
        'coupon_name'      => 'required|string|max:255',
        'coupon_desc'      => 'nullable|string',
        'discount_type'    => 'required|in:percentage,fixed,free delivery',
        'discount_value'   => 'nullable|numeric|min:0',
        'min_spend'        => 'nullable|numeric|min:0',
        'max_discount'     => 'nullable|numeric|min:0',
        'usage_limit'      => 'nullable|integer|min:1',
        'usage_per_user'   => 'nullable|integer|min:1',
        'auto_apply'    => 'required|in:Yes,No',
        'product_id'       => 'nullable|integer',
        'region_code'      => 'nullable|string',
        'province_code'    => 'nullable|string',
        'city_code'        => 'nullable|string',
        'barangay_code'    => 'nullable|string',
        'start_date'       => 'nullable|date',
        'start_time'       => 'nullable',
        'end_date'         => 'nullable|date|after_or_equal:start_date',
        'end_time'         => 'nullable',
        'status'           => 'required|in:0,1',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();
    }

    $data = [
            'coupon_name'     => $request->coupon_name,
            'coupon_desc'     => $request->coupon_desc,
            'code'            => $request->coupon_code,
            'discount_type'   => $request->discount_type,
            'discount_value'  => $request->discount_value,
            'min_spend'       => $request->min_spend,
            'max_discount'    => $request->max_discount,
            'usage_limit'     => $request->usage_limit,
            'usage_per_user'  => $request->usage_per_user,
            'is_auto_apply'   => $request->is_auto_apply,
            'product_id'      => $request->product_id,
            'region_code'     => $request->region_code,
            'province_code'   => $request->province_code,
            'city_code'       => $request->city_code,
            'barangay_code'   => $request->barangay_code,
            'start_date'      => $request->start_date,
            'start_time'      => $request->start_time,
            'end_date'        => $request->end_date,
            'end_time'        => $request->end_time,
            'status'          => $request->status,
            'updated_at'      => now(),
        ];

 
    DB::table('coupon_new')->where('id', $id)->update($data);

    return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully.');
}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {

        // dd($request->all());
        Validator::make($request->all(), [
            'name' => 'required|max:150|unique:coupons,name,' . $coupon->id,
            'description' => 'required',
            'terms_and_conditions' => 'required',
            'customer' => $request->coupon_scope == 'specific' ? 'required' : '',
            'code' => $request->coupon_activation == 'manual' ? 'required' : '',
            'reward' => 'required',
            'location' => $request->reward == 'free-shipping-optn' ? 'required' : '',
            'shipping_fee_discount_amount' => ($request->reward == 'free-shipping-optn' && $request->discount_type == 'partial') ? 'required|min:1' : '',
            'discount_amount' => $request->reward == 'discount-amount-optn' ? 'required' : '',
            'discount_percentage' => $request->reward == 'discount-percentage-optn' ? 'required' : '',
            'free_product_id' => $request->reward == 'free-product-optn' ? 'required' : '',

        ])->validate();

        $data = $request->all();

        $loc = '';
        if($request->reward == 'free-shipping-optn'){
          
            $locations = $data['location'];
            $loc_discount_type = $request->discount_type;
            $loc_discount_amount = $request->shipping_fee_discount_amount;

            foreach($locations as $l){
                $loc .= $l.'|';
            }  
        } else {
            $loc = NULL;
            $loc_discount_type = NULL;
            $loc_discount_amount = 0;
        }

        $customernames = '';
        if(isset($request->customer)){
            $customers = $data['customer'];
            foreach($customers as $c){
                $customernames .= $c.'|';
            }
        }

        $productids = '';
        if(isset($request->free_product_id)){
            $products_ids = $data['free_product_id'];
            foreach($products_ids as $prod){
                $productids .= $prod.'|';
            }
        }

        $amount_discount = 1;
        if($request->reward == 'discount-amount-optn' || $request->reward == 'discount-percentage-optn'){
            $amount_discount = $request->amount_discount;
        }

        $discount_productid = NULL;
        if($request->product_discount == 'current'){
            $discount_productid = NULL;
        }

        if($request->product_discount == 'specific'){
            $discount_productid = $request->discount_productid;
        }

        Coupon::find($coupon->id)->update([
            'coupon_code' => $request->coupon_activation == 'manual' ? $request->code : ($request->name ?? $request->name),
            'name' => $request->name,
            'description' => $request->description,
            'terms_and_conditions' => $request->terms_and_conditions,
            'activation_type' => $request->coupon_activation,
            'customer_scope' => $request->coupon_scope,
            'scope_customer_id' => $request->coupon_scope == 'specific' ? $customernames : NULL,
            'location' => $loc,
            'location_discount_type' => $loc_discount_type,
            'location_discount_amount' => $request->discount_type == 'full' ? null : $loc_discount_amount,
            'reward' => $request->reward,
            'amount' => $request->reward == 'discount-amount-optn' ? $request->discount_amount : NULL,
            'percentage' => $request->reward == 'discount-percentage-optn' ? $request->discount_percentage : NULL,
            'free_product_id' => ($request->reward) == 'free-product-optn' ? $productids : NULL,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'product_discount' => $request->amount_discount == 2 ? $request->product_discount : NULL,
            'discount_product_id' => $discount_productid,
            // 'availability' => ($request->has('availability')) ? 1 : 0,
            'user_id' => Auth::id(),
        ]);

        if($coupon){
            
            $this->update_coupon_time_settings($coupon->id,$request);            
            $this->update_coupon_purchase_settings($coupon->id,$request);
            // $this->update_coupon_activity_settings($coupon->id,$request);
            $this->update_coupon_rule_settings($coupon->id,$request);
        }

        return back()->with('success','Coupon details has been updated.');
    }

    public function update_coupon_time_settings($couponID,$request)
    {
        $starttime = Carbon::parse($request->starttime)->format('H:i');
        $endtime = Carbon::parse($request->endtime)->format('H:i');
        
        Coupon::find($couponID)->update([
            'start_date' => $request->coupon_time[0] == 'datetime' ? $request->startdate : NULL,
            'end_date' => $request->coupon_time[0] == 'datetime' ? $request->enddate : NULL,
            'start_time' => isset($request->starttime) ? $starttime : NULL,
            'end_time' => isset($request->endtime) ? $endtime : NULL,
            'event_name' => $request->coupon_time[0] == 'custom' ? $request->eventname : NULL,
            'event_date' => $request->coupon_time[0] == 'custom' ? $request->eventdate : NULL,
            'repeat_annually' => $request->has('repeat_annually') ? 1 : 0,
        ]);
    }

    public function update_coupon_purchase_settings($couponID,$request)
    {   
        $data = $request->all();
        $productnames = NULL;
        $productcategories = NULL;
        $productbrand = NULL;
        $totalamount = NULL;
        $totalqty = NULL;
        $amounttype = NULL;
        $qtytype = NULL;

        $coupon_combination_counter = 0;
        $coupon_combination = '';

        if($request->has('purchase_product')){
            $coupon_combination_counter++;
            if(isset($request->product_name)){
                $prodname = $data['product_name'];
                $coupon_combination .= 'product|';
                foreach($prodname as $prod){
                    $productnames .= $prod.'|';
                }
            }

            if(isset($request->product_brand)){
                $prodbrand = $data['product_brand'];
                $coupon_combination .= 'product|';
                foreach($prodbrand as $brand){
                    $productbrand .= $brand.'|';
                }
            } else{
               if(isset($request->product_category)){
                    $prodcat = $data['product_category'];
                    $coupon_combination .= 'product|';
                    foreach($prodcat as $cat){
                        $productcategories .= $cat.'|';
                    }
                } 
            }
            
        }

        if($request->has('purchase_total_amount')){
            $coupon_combination .= 'amount|';
            $coupon_combination_counter++;
            $totalamount = $request->purchase_amount;
            $amounttype = $request->amount_opt;
        }

        if($request->has('purchase_total_qty')){
            $coupon_combination .= 'qty|';
            $coupon_combination_counter++;
            $totalqty = $request->purchase_qty;
            $qtytype = $request->qty_opt;
        }

        if ($request->has('coupon_setting') && count($request->coupon_setting) > 1) {
            Coupon::find($couponID)->update([
                'purchase_product_id' => $productnames,
                'purchase_product_cat_id' => $productcategories,
                'purchase_product_brand' => $productbrand,
                'purchase_amount' => $totalamount,
                'purchase_qty' =>  $totalqty,
                'purchase_amount_type' => $amounttype,
                'purchase_qty_type' =>  $qtytype,
                'purchase_combination_counter' => $coupon_combination_counter,
                'purchase_combination' => $coupon_combination
            ]);
        } else {
            Coupon::find($couponID)->update([
                'purchase_product_id' => null,
                'purchase_product_cat_id' => null,
                'purchase_product_brand' => null,
                'purchase_amount' => null,
                'purchase_qty' =>  null,
                'purchase_amount_type' => null,
                'purchase_qty_type' =>  null,
                'purchase_combination_counter' => null,
                'purchase_combination' => null
            ]);
        }
    }

    // public function update_coupon_activity_settings($couponID,$request)
    // {
    //     Coupon::find($couponID)->update([
    //         'activity_type' => $request->coupon_activity[0],
    //         'org_name' => $request->coupon_activity[0] == 'feat_organization' ? $request->org_name : NULL,
    //         'inactive_no' => $request->coupon_activity[0] == 'returning_customer' ? $request->inactive_no : NULL,
    //         'inactive_type' => $request->coupon_activity[0] == 'returning_customer' ? $request->coupon_return_customer : NULL,
    //     ]);
    // }

    public function update_coupon_rule_settings($couponID,$request)
    {
        Coupon::find($couponID)->update([
            'customer_limit' => isset($request->customer_limit) ? $request->coupon_customer_limit_qty : 100000,
            // 'usage_limit' => isset($request->usage_limit) ? $request->usage_limit[0] : NULL,
            // 'usage_limit_no' => $request->usage_limit[0] == 'multiple_use' ? $request->multi_usage_limit_qty : NULL,
            'combination' => ($request->has('combination')) ? 1 : 0,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        //
    }

    public function update_status($id,$status)
    {
        Coupon::find($id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.coupons.status_update_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $coupon = Coupon::findOrFail($request->coupons);
        $coupon->update([ 'user' => Auth::id() ]);
        $coupon->delete();

        return back()->with('success', __('standard.coupons.single_delete_success'));
    }

    public function restore($coupon){
        Coupon::withTrashed()->find($coupon)->update(['status' => 'INACTIVE','user_id' => Auth::id() ]);
        Coupon::whereId($coupon)->restore();

        return back()->with('success', __('standard.coupons.restore_promo_success'));
    }

    public function multiple_change_status(Request $request)
    {
        $coupons = explode("|", $request->coupons);

        foreach ($coupons as $coupon) {
            $publish = Coupon::where('status', '!=', $request->status)->whereId($coupon)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.coupons.multiple_status_update_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $coupons = explode("|",$request->coupons);

        foreach($coupons as $coupon){
            Coupon::whereId($coupon)->update(['user_id' => Auth::id() ]);
            Coupon::whereId($coupon)->delete();
        }

        return back()->with('success', __('standard.coupons.multiple_delete_success'));
    }

    public function sales_list(Request $request)
    {
        
        $qry= "SELECT h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,p.brand,p.code,pay.payment_date as pdate,p.brand FROM `ecommerce_sales_details` d 
            left join ecommerce_sales_headers h on h.id=d.sales_header_id 
            left join ecommerce_sales_payments pay on pay.sales_header_id=d.sales_header_id
            left join products p on p.id=d.product_id 
            left join product_categories c on c.id=p.category_id
            where h.id>0 and h.status<>'CANCELLED' and h.delivery_status<>'CANCELLED'
            ";
       
        // else{
        //     $qry = "SELECT h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,p.brand,p.code FROM `ecommerce_sales_details` d left join ecommerce_sales_headers h on h.id=d.sales_header_id left join products p on p.id=d.product_id left join product_categories c on c.id=p.category_id where h.id>0 and h.status<>'CANCELLED' and h.delivery_status<>'CANCELLED'";
        // }

        if(isset($_GET['brand']) && $_GET['brand']<>''){
            $qry.= " and p.brand='".$_GET['brand']."'";
        }
        if(isset($_GET['customer']) && $_GET['customer']<>''){
            $qry.= " and h.customer_name='".$_GET['customer']."'";
        }
        if(isset($_GET['product']) && $_GET['product']<>''){
            $qry.= " and d.product_id='".$_GET['product']."'";
        }
        if(isset($_GET['category']) && $_GET['category']<>''){
            $qry.= " and p.category_id='".$_GET['category']."'";
        }
        if(isset($_GET['payment_status']) && $_GET['payment_status']<>''){
            $qry.= " and h.payment_status='".$_GET['payment_status']."'";
        }
        if(isset($_GET['del_status']) && $_GET['del_status']<>''){
            $qry.= " and h.delivery_status='".$_GET['del_status']."'";
        }
      
        if(isset($_GET['start']) && strlen($_GET['start'])>=1){
             $qry.= " and h.created_at >='".$_GET['start']." 00:00:00.000' and h.created_at <='".$_GET['end']." 23:59:59.999'";
            
        }

        
        //dd($qry);

        $rs = DB::select($qry. " ORDER BY h.id desc");

        return view('admin.reports.sales.list',compact('rs'));

    }

    public function coupon_list(Request $request)
    {
        $params = [];
        $qry = "
            SELECT 
                h.order_number, 
                h.net_amount, 
                h.customer_name, 
                c.name, 
                cs.coupon_code, 
                cs.customer_id 
            FROM coupon_sales cs
            LEFT JOIN ecommerce_sales_headers h ON h.id = cs.sales_header_id
            LEFT JOIN coupons c ON c.id = cs.coupon_id
            WHERE cs.id > 0
        ";

        if (!empty($request->coupon_code)) {
            $qry .= " AND cs.coupon_code = :coupon_code";
            $params['coupon_code'] = $request->coupon_code;
        }

        if (!empty($request->customer)) {
            $qry .= " AND cs.customer_id = :customer";
            $params['customer'] = $request->customer;
        }

        $rs = DB::select($qry, $params);

        return view('admin.reports.coupon.list', compact('rs'));
    }

    public function add_manual_coupon(Request $request)
    {
        $coupon = Coupon::whereRaw('LOWER(coupon_code) = ?', [strtolower($request->couponcode)])
            ->where('activation_type', 'manual')
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'status' => 'not_exist',
                'message' => 'Coupon not found.'
            ]);
        }

        // Combine start and end date/time
        $now = Carbon::now();
        $start = Carbon::parse("{$coupon->start_date} {$coupon->start_time}");
        $end = Carbon::parse("{$coupon->end_date} {$coupon->end_time}");

        // Check if current time is within allowed coupon window
        if ($now->lt($start)) {
            return response()->json([
                'success' => false,
                'status' => 'not_started',
                'message' => 'This coupon is not active yet. Please try again later.'
            ]);
        }

        if ($now->gt($end)) {
            return response()->json([
                'success' => false,
                'status' => 'expired',
                'message' => 'This coupon has already expired.'
            ]);
        }

        // Check if inactive
        if ($coupon->status !== 'ACTIVE') {
            return response()->json([
                'success' => false,
                'status' => strtolower('inactive'),
                'message' => 'Coupon is ' . strtolower('inactive') . '.'
            ]);
        }

        // Check if customer is allowed
        if ($coupon->customer_scope === 'specific') {
            $allowedIds = explode('|', $coupon->scope_customer_id ?? '');
            $allowedIds = array_filter($allowedIds, function($value) { return !is_null($value) && $value !== ''; });
            if (!in_array(Auth::id(), $allowedIds)) {
                return response()->json([
                    'success' => false,
                    'status' => 'not_allowed',
                    'message' => 'Sorry, you are not allowed to use this coupon.'
                ]);
            }
        }

        // Check if coupon was already applied
        $totalUsed = CouponCart::where('coupon_id', $coupon->id)->where('status', 1)->sum('total_usage');
        $customerUsed = CouponCart::where('coupon_id', $coupon->id)
            ->where('status', 1)
            // ->where('customer_id', Auth::id())
            ->sum('total_usage');

        if ($coupon->usage_limit !== null && $totalUsed >= $coupon->usage_limit) {
            return response()->json([
                'success' => false,
                'status' => 'limit_reached',
                'message' => 'This coupon has reached its total usage limit.'
            ]);
        }

        if ($coupon->customer_limit !== null && $customerUsed >= $coupon->customer_limit) {
            return response()->json([
                'success' => false,
                'status' => 'customer_limit_reached',
                'message' => 'You have reached your usage limit for this coupon.'
            ]);
        }

        $free_products = null;

        if ($coupon->free_product_id) {
            $freeProductIds = explode('|', $coupon->free_product_id);
            $freeProductIds = array_filter($freeProductIds, function($value) { return !is_null($value) && $value !== ''; });
            $free_products = Product::with('photos')->whereIn('id', $freeProductIds)->get();
        }

        // Validate cart data if coupon has purchase condition logic
        if ($coupon->purchase_combination) {
            // Parse incoming cart items
            if (Auth::check()) {
                $cartItems = Cart::where('user_id', Auth::id())->get();
            } else {
                $cartItems = collect(session('cart', []));
            }

            $cartQty = $cartItems->sum('qty');
            $cartTotal = $cartItems->sum(fn ($item) => ($item['price'] ?? 0) * ($item['qty'] ?? 0));

            $cartProductIds = $cartItems->pluck('product_id')->map(fn($id) => (string)$id)->toArray();
            // return empty coupon if cart product has category id of 1.
            $cartHasExcludedCategory = Product::whereIn('id', $cartProductIds)
                ->where('category_id', 1)
                ->exists();

            if ($cartHasExcludedCategory) {
                return response()->json([
                    'success' => true,
                    'status' => 'excluded_category',
                    'coupons' => []
                ]);
            }
            
            // Total Quantity Condition
            if ($coupon->purchase_qty && $coupon->purchase_qty > 0) {
                if ($coupon->purchase_qty_type === 'min' && $cartQty < $coupon->purchase_qty) {
                    return response()->json([
                        'success' => false,
                        'status' => 'quantity_requirement_failed',
                        'message' => 'Your cart does not meet the required quantity for this coupon.',
                    ]);
                }

                if ($coupon->purchase_qty_type === 'max' && $cartQty > $coupon->purchase_qty) {
                    return response()->json([
                        'success' => false,
                        'status' => 'quantity_requirement_failed',
                        'message' => 'Your cart exceeds the allowed quantity for this coupon.',
                    ]);
                }
            }

            // Total Amount Condition
            if ($coupon->purchase_amount && $coupon->purchase_amount > 0) {
                if ($cartTotal < $coupon->purchase_amount) {
                    return response()->json([
                        'success' => false,
                        'status' => 'amount_requirement_failed',
                        'message' => 'Your order amount does not meet the required total for this coupon.',
                    ]);
                }

                if ($coupon->purchase_amount_type === 'max' && $cartTotal > $coupon->purchase_amount) {
                    return response()->json([
                        'success' => false,
                        'status' => 'amount_requirement_failed',
                        'message' => 'Your order exceeds the allowed total for this coupon.',
                    ]);
                }
            }

            // Product Matching Condition
            if ($coupon->purchase_product_id && $coupon->purchase_product_id) {
                $requiredIds = explode('|', $coupon->purchase_product_id);
                $cartProductIds = $cartItems->pluck('product_id')->map(fn($id) => (string)$id)->toArray();

                $hasRequiredProduct = false;
                foreach ($requiredIds as $requiredId) {
                    if (in_array((string)$requiredId, $cartProductIds)) {
                        $hasRequiredProduct = true;
                        break;
                    }
                }

                if (!$hasRequiredProduct) {
                    return response()->json([
                        'success' => false,
                        'status' => 'product_requirement_failed',
                        'message' => 'This coupon requires specific product(s) in your cart.',
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'status' => 'valid',
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->coupon_code,
                'name' => $coupon->name,
                'description' => $coupon->description,
                'terms' => $coupon->terms_and_conditions,

                'discount_type' => $coupon->percentage > 0 ? 'percent' : 'amount',
                'discount' => $coupon->percentage > 0 ? $coupon->percentage : $coupon->amount,
                'applies_to' => $coupon->free_product_id ? 'free_product' : ($coupon->purchase_product_id ? 'product' : 'cart'),
                'purchase_product_id' => $coupon->purchase_product_id,
                'free_products' => $free_products ?? [],
                'combination_allowed' => $coupon->combination == 1,
                'total_usage_limit' => $coupon->usage_limit,
                'total_usage_used' => $totalUsed,
                'customer_limit' => $coupon->customer_limit,
                'customer_usage_used' => $customerUsed,
                'status' => 'valid',
                'location' => $coupon->location,
                'reward' => $coupon->reward,
                'free_shipping' => $coupon->reward == 'free-shipping-optn',
                'free_shipping_discount_amount' => ($coupon->reward == 'free-shipping-optn' && $coupon->location_discount_type == 'partial') ? $coupon->location_discount_amount : ($coupon->reward == 'free-shipping-optn' ? 100 : 0),
            ]
        ]);
    }

    public function get_auto_coupons(Request $request)
    {
        $now = Carbon::now();

        $eligibleCoupons = Coupon::where('activation_type', 'auto')
            ->where('status', 'ACTIVE')
            ->whereRaw("CONCAT(start_date, ' ', start_time) <= ?", [$now])
            ->whereRaw("CONCAT(end_date, ' ', end_time) >= ?", [$now])
            ->where(function ($q) {
                $q->whereNull('customer_scope')
                    ->orWhere('customer_scope', 'all');
            })
            ->get();

        $result = [];

        // Get cart
        $cartItems = Auth::check()
            ? Cart::where('user_id', Auth::id())->get()
            : collect(session('cart', []));

        $cartQty = $cartItems->sum('qty');
        $cartTotal = $cartItems->sum(fn($item) => ($item['price'] ?? 0) * ($item['qty'] ?? 0));
        $cartProductIds = $cartItems->pluck('product_id')->map(fn($id) => (string)$id)->toArray();

        // return empty coupon if cart product has category id of 1.
        $cartHasExcludedCategory = Product::whereIn('id', $cartProductIds)
            ->where('category_id', 1)
            ->exists();

        if ($cartHasExcludedCategory) {
            return response()->json([
                'success' => true,
                'coupons' => []
            ]);
        }

        foreach ($eligibleCoupons as $coupon) {
            // Check usage limits
            $totalUsed = CouponCart::where('coupon_id', $coupon->id)->where('status', 1)->sum('total_usage');
            $customerUsed = CouponCart::where('coupon_id', $coupon->id)
                ->where('status', 1)
                ->sum('total_usage');

            if ($coupon->usage_limit !== null && $totalUsed >= $coupon->usage_limit) continue;
            if ($coupon->customer_limit !== null && $customerUsed >= $coupon->customer_limit) continue;

            // Purchase condition logic
            if ($coupon->purchase_combination) {
                // Quantity Condition
                if ($coupon->purchase_qty && $coupon->purchase_qty > 0) {
                    if ($coupon->purchase_qty_type === 'min' && $cartQty < $coupon->purchase_qty) continue;
                    if ($coupon->purchase_qty_type === 'max' && $cartQty > $coupon->purchase_qty) continue;
                }

                // Amount Condition
                $combi = explode('|', $coupon->purchase_combination ?? '');
                if ($coupon->purchase_amount && $coupon->purchase_amount > 0) {
                    if ($cartTotal < $coupon->purchase_amount) continue;
                    // if ($coupon->purchase_amount_type === 'max' && $cartTotal > $coupon->purchase_amount) continue;
                }

                // Product Condition
                if ($coupon->purchase_product_id && $coupon->purchase_product_id) {
                    $requiredIds = explode('|', $coupon->purchase_product_id);
                    $hasRequiredProduct = false;
                    foreach ($requiredIds as $requiredId) {
                        if (in_array((string)$requiredId, $cartProductIds)) {
                            $hasRequiredProduct = true;
                            break;
                        }
                    }
                    if (!$hasRequiredProduct) continue;
                }
            }

            // Free products
            $free_products = null;
            if ($coupon->free_product_id) {
                $freeProductIds = explode('|', $coupon->free_product_id);
                $freeProductIds = array_filter($freeProductIds, fn($val) => !is_null($val) && $val !== '');
                $free_products = Product::with('photos')->whereIn('id', $freeProductIds)->get();
            }

            $result[] = [
                'id' => $coupon->id,
                'code' => $coupon->coupon_code,
                'name' => $coupon->name,
                'description' => $coupon->description,
                'terms' => $coupon->terms_and_conditions,
                'type' => $coupon->amount_discount_type == 1 ? 'amount' : 'product',
                'discount_type' => $coupon->reward == 'free-shipping-optn'
                    ? ($coupon->location_discount_type == 'partial' ? 'amount' : 'percent')
                    : ($coupon->percentage > 0 ? 'percent' : 'amount'),
                'discount' => $coupon->percentage > 0 ? $coupon->percentage : $coupon->amount,
                'applies_to' => $coupon->free_product_id ? 'free_product' : ($coupon->purchase_product_id ? 'product' : 'cart'),
                'purchase_product_id' => $coupon->purchase_product_id,
                'free_products' => $free_products ?? [],
                'combination_allowed' => $coupon->combination == 1,
                'total_usage_limit' => $coupon->usage_limit,
                'total_usage_used' => $totalUsed,
                'customer_limit' => $coupon->customer_limit,
                'customer_usage_used' => $customerUsed,
                'status' => 'valid',
                'location' => $coupon->location,
                'reward' => $coupon->reward,
                'free_shipping' => $coupon->reward == 'free-shipping-optn',
                'free_shipping_discount_amount' => $coupon->reward == 'free-shipping-optn'
                    ? ($coupon->location_discount_type == 'partial' ? $coupon->location_discount_amount : 100)
                    : 0,
            ];
        }

        return response()->json([
            'success' => true,
            'coupons' => $result
        ]);
    }

public function getCategories() {
    $categories = DB::table('product_categories')
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get();

    return response()->json($categories);
}

public function getProducts(Request $request) {
    $query = DB::table('products')
                ->where('category_id', $request->category_id)
                ->orderBy('name')
                ->select('id', 'name')
                ->get();

    return response()->json($query);
}


    public function redeem($id)
{
    $userId = Auth::id();


    $coupon = DB::table('coupon_new')
        ->where('id', $id)
        ->where('status', 1)
        ->first();

    if (!$coupon) {
        return response()->json(['error' => 'Coupon not found or inactive.'], 404);
    }

    $exists = DB::table('coupon_redemptions')
        ->where('user_id', $userId)
        ->where('coupon_id', $id)
        ->exists();

    if ($exists) {
        return response()->json(['error' => 'You already redeemed this coupon.'], 409);
    }


    DB::table('coupon_redemptions')->insert([
        'user_id' => $userId,
        'coupon_id' => $id,
        'redeemed_at' => now()
    ]);

    return response()->json(['success' => 'Coupon redeemed successfully.']);
}
public function getUserCoupons()
{
    $userId = auth()->id();

    $coupons = DB::table('coupon_new')
    ->where('start_date', '<=', now())
    ->where('end_date', '>=', now())
    ->whereIn('id', function($q) use ($userId) {
        $q->select('coupon_id')
          ->from('coupon_redemptions')
          ->where('user_id', $userId);
    })
    ->get();

    return response()->json(['coupons' => $coupons]);
}
}
