<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\Http\Controllers\Controller;

use App\Models\Album;
use App\Models\Permission;
use App\Helpers\ListingHelper;
use App\Models\Page;
use App\Models\Approvals;
use App\Models\Sms;
use App\Models\User;
use App\Models\Deliverablecities;
use App\Models\Product;

use App\EcommerceModel\GiftCertificate;
use App\EcommerceModel\SalesDetail;
use App\EcommerceModel\DeliveryStatus;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesPayment;

use App\Mail\PaymentSubmittedAdmin;
use App\Mail\PaymentApprovedAdmin;
use App\Mail\PaymentDisapprovedAdmin;
use App\Mail\DeliveryMovement;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\EcommerceModel\Branch;
use App\EcommerceModel\JobOrder;
use App\Mail\DeliveryAssignedMail;
use App\Mail\DeliveryAssignedMultipleMail;
use App\Mail\ManualOrderCancelledByAdminMail;
use App\Models\ActivityLog;
use App\Models\DeliveriesImage;
use App\Models\ProductDeliveryAddress;
use App\Models\Role;
use App\Models\UserBranch;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class SalesController extends Controller
{
    private $searchFields = ['order_number','customer_name'];

    public function __construct()
    {
        // Permission::module_init($this, 'sales_transaction');
    }

    public function edit_items(){
        $items = SalesDetail::where('sales_header_id',$_GET['id'])->get();
        $products = Product::orderBy('name')->get();
        return view('admin.sales.update_items',compact('items','products'));
    }

    public function update_items(Request $request){
        $head = SalesHeader::whereId($request->ui_sales_id)->first();
        
        if (
            auth()->user()->has_access_to_route('sales-transaction.update') &&
            $head->isConfirmedAndPastCutoffAndForecasted()) {
                return redirect()->route('sales-transaction.index')->with('error', 'Confirmed orders past cutoff and forecasted cannot be updated.');
        }
        
        $date_needed = '';
        foreach($head->items as $item){
            if(!empty($item->delivery_date)){
                $date_needed = $item->delivery_date;
            }
            if($request->has('uia_product'.$item->id)){
                $paella_price = 0;
                $paella_qty = 0;           
                $gross = $request->input('uiu_qty'.$item->id) * $item->price;
                if(!empty($request->input('uiu_paella'.$item->id))){
                    $paella_price = $request->input('uiu_qty'.$item->id) * $request->input('uiu_paella'.$item->id);
                    $paella_qty = $request->input('uiu_qty'.$item->id);
                    $gross = ($request->input('uiu_qty'.$item->id) * $item->price) + ($request->input('uiu_qty'.$item->id) * $request->input('uiu_paella'.$item->id));
                }
                SalesDetail::where('id', $item->id)
                    ->get()
                    ->each(function ($e) use ($paella_price, $paella_qty, $request, $item, $gross) {
                        $e->update([
                            'paella_price' => $paella_price,
                            'paella_qty' => $paella_qty,
                            'qty' => $request->input('uiu_qty'.$item->id),
                            'gross_amount' => $gross,
                            'net_amount' => $gross
                        ]);
                    });
            } else {
                SalesDetail::where('id', $item->id)
                    ->get()
                    ->each(function ($e) {
                        $e->forceDelete();
                    });
            }
            
        }

        for($x = 1; $x <= $request->ui_total_new; $x++){
            if($request->has('uia_product'.$x)){
                $paella_price = 0;
                $paella_qty = 0;           
                $gross = $request->input('uia_qty'.$x) * $request->input('uia_price'.$x);
                if(!empty($request->input('uia_paella'.$x))){
                    $paella_price = $request->input('uia_qty'.$x) * $request->input('uia_paella'.$x);
                    $paella_qty = $request->input('uia_qty'.$x);
                    $gross = ($request->input('uia_qty'.$x) * $request->input('uia_price'.$x)) + ($request->input('uia_qty'.$x) * $request->input('uia_paella'.$x));
                }
                $p = Product::whereId($request->input('uia_product'.$x))->first();
                $update = SalesDetail::create([
                    'sales_header_id' => $head->id,
                    'product_id' => $request->input('uia_product'.$x),
                    'product_name' => $p->name,
                    'product_category' => $p->category_id,
                    'price' => $request->input('uia_price'.$x),
                    'cost' => 0,
                    'tax_amount' => 0,
                    'promo_id' => 0,
                    'promo_description' => '',
                    'discount_amount' => 0,
                    'gross_amount' => $gross,
                    'net_amount' => $gross,
                    'qty' => $request->input('uia_qty'.$x),
                    'paella_qty' => $paella_qty,
                    'uom' => $p->uom,
                    'size' => $p->size ?? "",
                    'no_of_pax' => $p->no_of_pax ?? "",
                    'paella_price' => $paella_price,
                    'other_cost' => 0,
                    'other_cost_description' => '',
                    'created_by' => Auth::id(),
                    'delivery_date' => $date_needed

                ]);
            }
        }
        $this->update_header_details($head);

        if(!request()->has('from_update_all')){
            return back()->with('success','Sales details has been updated!');
        }

        return back()->with('success','Successfully updated sales record');
    }

    public function update_all(Request $request)
    {
        // dd($request->all());
        $request->merge(['from_update_all' => true]);

        $this->update_dateneeded($request);
        $this->update_items($request);

        return back()->with('success', 'Sales details and items successfully updated!');
    }

    public function update_header_details($sales){

        $rate_type= 'misc';
        $baka = 0;
        $lechon = 0;
        $gross = 0;
        $delivery_amount=0;
        $details = SalesDetail::where('sales_header_id',$sales->id)->get();
        foreach($details as $item){
            $gross+=$item->gross_amount;
            if($item->product_id == 178){
                $baka = 1;
            }
            if($item->product->production_item){
                $lechon = 1;
            }            
        }

        if($sales->delivery_type == 'Door to door delivery'){            
            if($lechon == 1 || $baka == 1){
                $rate_type = 'lechon';
            }
            $delivery_amount = 0;
            
            if($sales->customer_location <> 'Other'){
                // $del_fee = Deliverablecities::where('name',$sales->customer_location)->where('is_active', 1)->where('item_type',$rate_type)->first();
               
                // $delivery_amount = $del_fee?->rate ?? 0;

                // if($baka == 1){
                //     $delivery_amount = 3500;
                // }
                $delivery_amount = $sales->delivery_fee_amount;
            }
            else{
                $delivery_amount = $sales->delivery_fee_amount;
            }
        }
        SalesHeader::where('id', $sales->id)
            ->get()
            ->each(function ($header) use ($delivery_amount, $gross, $sales) {
                $header->update([
                    'delivery_fee_amount' => $delivery_amount,
                    'gross_amount' => ($gross + $delivery_amount), 
                    'net_amount' => ($gross + $delivery_amount) - $sales->discount_amount,
                    'payment_status' => 'UNPAID'
                ]);
        });
        
    }

    public function prepare_dateneeded(Request $request){
        $salesdetail = SalesDetail::where('sales_header_id',$request->id)->first();
        $salesheader = SalesHeader::find($request->id);

        $dateneeded = '';
        $date_only = '';
        $time_only = '';
        $locationed = '';
        if(!empty($salesdetail)){
            $date_only = date('Y-m-d',strtotime($salesdetail->delivery_date));
            $time_only = date('H:i',strtotime($salesdetail->delivery_date));
            $dateneeded = date('Y-m-d H:i A',strtotime($salesdetail->delivery_date));
        }

        if($salesheader->delivery_type == 'Door to door delivery'){
            $locationed = $salesheader->customer_location;
        }
        if($salesheader->delivery_type == 'Store Pickup'){
            $locationed = $salesheader->outlet;
        }

        return response()->json([
            'id' => $salesheader->id,
            'dateneeded' => $dateneeded,
            'date_only' => $date_only,
            'time_only' => $time_only,
            'delivery_type' => $salesheader->delivery_type,
            'location' => $locationed,
            'instruction' => $salesheader->instruction,
            'delivery_address' => $salesheader->customer_delivery_adress
        ]);
    }

    public function update_dateneeded(Request $request){
        $sales = SalesHeader::findOrFail($request->update_dateneeded_id);
    
        if (
            auth()->user()->has_access_to_route('sales-transaction.update') &&
            $sales->isConfirmedAndPastCutoffAndForecasted()) {
                return redirect()->route('sales-transaction.index')->with('error', 'Confirmed orders past cutoff and forecasted cannot be updated.');
        }


        // if(isset($request->delivery_branch)){
        //     SalesHeader::whereId($request->update_dateneeded_id)->update(['delivery_branch' => $request->delivery_branch]);
        // }
        
        if ($request->has('open_date') && $request->open_date == 'on') {
            SalesHeader::whereId($request->update_dateneeded_id)->update([
                'delivery_status' => 'Open Date'
            ]);

            SalesDetail::where('sales_header_id',$request->update_dateneeded_id)->update([
                'delivery_date' => null
            ]);

        } else {
            if ($sales->delivery_status == 'Open Date') {
                SalesHeader::whereId($request->update_dateneeded_id)->update([
                    'delivery_status' => ''
                ]);
            } else {
                SalesHeader::whereId($request->update_dateneeded_id)->update([
                    'delivery_status' => $sales->delivery_status // keep existing status if not open date
                ]);
            }
        }

        if ($request->has('update_dateneeded_date') && $request->has('update_dateneeded_time')) {
            SalesDetail::where('sales_header_id', $request->update_dateneeded_id)
                ->get()
                ->each(function ($detail) use ($request) {
                    $detail->update([
                        'delivery_date' =>
                            $request->update_dateneeded_date . ' ' . $request->update_dateneeded_time
                    ]);
                });
        }

        if ($request->shipping_type == 'storepickup' && auth()->user()->has_access_to_route('sales.update_delivery_branch')) {
            $request->validate([
                'update_dateneeded_sp' => 'required',
            ], [
                'update_dateneeded_sp.required' => 'Location is required.',
            ]);
        }

        // Insert new addresses
        if ($request->filled('address')) {
            ProductDeliveryAddress::where('sales_header_id', $request->update_dateneeded_id)
                ->get()
                ->each
                ->delete();

            foreach ($request->address as $index => $addr) {

                $prods = [];

                $products = Product::whereIn('id', $request->product_ids[$index])->get();

                SalesDetail::where('sales_header_id',$request->update_dateneeded_id)
                        ->get()
                        ->each(function ($detail) use ($request, $index) {
                            $detail->update([
                                'delivery_date' =>
                                    $request->dateneeded_date[$index] . ' ' . $request->dateneeded_time[$index]
                            ]);
                        });


                foreach ($products as $product) {
                    $prods[] = [
                        'qty' => $request->product_qty[$index][$product->id] ?? 0,
                        'product_id' => $product->id,
                        'product' => $product->toArray(),
                    ];
                }

                if (!empty($addr)) {
                    ProductDeliveryAddress::create([
                        'sales_header_id' => $request->update_dateneeded_id,
                        'address' => $addr,
                        'delivery_date' => $request->dateneeded_date[$index] ?? null,
                        'delivery_time' => $request->dateneeded_time[$index] ?? null,
                        'delivery_fee' => $request->delivery_fee[$index] ?? 0,
                        'user_id' => $sales->user_id,
                        'branch' => $request->branch[$index] ?? null,
                        'location' => $request->city[$index] . ', ' . $request->province[$index] ?? null,
                        'note' => $request->note[$index] ?? null,
                        'contact_person' => $request->contact_person[$index] ?? null,
                        'contact_tel' => $request->contact_tel[$index] ?? null,
                        'products' => json_encode($prods),
                        'province' => $request->province[$index] ?? null,
                        'city' => $request->city[$index] ?? null,
                        'barangay' => $request->barangay[$index] ?? null,
                        'qty' => array_sum($request->product_qty[$index] ?? []),
                        'paella_price' => 0,
                    ]);
                }
            }
        }

        if($request->shipping_type == 'd2d'){
            $sales->update(['delivery_type' => 'Door to door delivery']);
            $rate_type= 'misc';
            $baka = 0;
            $lechon = 0;
            $details = SalesDetail::where('sales_header_id',$request->update_dateneeded_id)->get();
            foreach($details as $d){
                if($d->product_id == 178){
                    $baka = 1;
                }
                if($d->product->production_item==1 && $d->product->is_misc<>1){
                    $lechon = 1;
                }
            }
            if($lechon == 1 || $baka == 1){
                $rate_type = 'lechon';
            }
            $delivery_amount = 0;
            if($request->update_dateneeded_d2d == 'Other'){
                $delivery_amount = $request->delivery_fee_amount;
            }
            else{
                if ($sales->delivery_fee_amount == 0) {
                    $del_fee = Deliverablecities::where('name',$request->update_dateneeded_d2d)->where('item_type',$rate_type)->first();           
                    $delivery_amount = $del_fee->rate;
                } else {
                    $delivery_amount = $sales->delivery_fee_amount;
                }
            }

            $amt = $sales->items->sum('gross_amount') + $delivery_amount;
            // $amt = ($sales->gross_amount - $sales->delivery_fee_amount) + $delivery_amount;

            if($sales->customer_location == $request->update_dateneeded_d2d){

                $sales->update([               
                    'customer_delivery_adress' => $request->new_delivery_address,
                    'instruction' => $request->new_instruction,
                    'delivery_fee_amount' => $delivery_amount,
                    'gross_amount' => $sales->items->sum('gross_amount'),
                    'net_amount' => $amt,
                    'customer_address' => '',
                    'delivery_branch' => $request->delivery_branch ?? $sales->delivery_branch
                ]);

            }else{

                $sales->update([
                    'customer_location' => $request->update_dateneeded_d2d,
                    'customer_delivery_adress' => $request->new_delivery_address,
                    'instruction' => $request->new_instruction,
                    'delivery_fee_amount' => $delivery_amount,
                    'gross_amount' => $amt,
                    'net_amount' => $amt,
                    'customer_address' => '',
                    'delivery_branch' => $request->delivery_branch ?? $sales->delivery_branch
                ]);

            }
        }
        if($request->shipping_type == 'storepickup'){
            $gross = $sales->items->sum('gross_amount');
            $sales->update([
                'customer_delivery_adress' => $request->update_dateneeded_sp,
                'instruction' => $request->new_instruction,
                'outlet' => $request->update_dateneeded_sp,
                'gross_amount' => $gross,
                'net_amount' => $gross,
                'delivery_fee_amount' => 0,
                'delivery_type' => 'Store Pickup',
                'customer_address' => $request->update_dateneeded_sp,
                'customer_location' => '',
                'delivery_branch' => null
            ]);
        }

        if(!request()->has('from_update_all')){
            return back()->with('success','Sales details has been updated!');
        }

        return back()->with('success','Sales details has been updated!');
    }

    public function approve_payment(Request $request){

        $orig_payment = SalesPayment::whereId($request->confirm_payment_id)->first();
        $image_url = $orig_payment->file_url;
        if($request->hasFile('confirm_payment_file')) {
            $newFile = $this->upload_file_to_storage('payments', $request->file('confirm_payment_file'));
            $image_url = $newFile['url'];
        }

        SalesPayment::where('id', $request->confirm_payment_id)
            ->get()
            ->each(function ($payment) use ($image_url, $request, $orig_payment) {
                $payment->update([
                    'status' => 'PAID',
                    'file_url' => $image_url,
                    'receipt_number' => $request->confirm_payment_ref ?? $orig_payment->receipt_number
                ]);
            });
        $data = SalesPayment::whereId($request->confirm_payment_id)->first();
        if($data->payment_type == 'Gift Cert'){

            $update_gift_cert = GiftCertificate::where('code',$data->receipt_number)->where('isApproved','<>','1')
                                ->get()
                                ->each(function ($gc) {
                                    $gc->update([
                                        'isApproved' => '1',
                                        'approved_by' => Auth::user()->name,
                                        'approved_on' => date('Y-m-d')
                                    ]);
                                });
            if($update_gift_cert){
                $coupon_amount = 0;
                $discounts = SalesPayment::where('sales_header_id',$data->sales_header_id)->whereStatus('PAID')->sum('amount');
                $grand_gross = $data->gross_amount - $discounts;
                $coupon_amount = $discounts;
                SalesHeader::whereId($data->sales_header_id)
                    ->get()->each(function ($sh) use ($grand_gross, $coupon_amount) {
                        $sh->update([
                            'net_amount' => $grand_gross,
                            'discount_amount' => $coupon_amount
                        ]);
                    });
            }
        }

        if ($data->is_discount == 1) {
            $discount = $data->amount;
        }

        $ran = microtime();
        $today = getdate();
        $approvalId = 'approval_'.$today[0].substr($ran, 2,6);
        $approval_store = Approvals::create([
            'approval_code' => $approvalId,
            'user_id' => Auth::id(),
            'approval_type' => 'Payment',
            'reference_id' => $data->sales_header_id,
            'remarks' => $request->confirm_payment_remarks,
            'payment_id' => $orig_payment->id
        ]);


        if(!empty($data->sales->email)){
           Mail::to($data->sales->email)->send(new PaymentApprovedAdmin($data));
        }


        $sales = SalesHeader::whereId($data->sales_header_id)->first();

        if ($discount > 0) {
            $sales->discount_amount = floatval($sales->discount_amount + $discount);
            $sales->net_amount = floatval($sales->net_amount) - floatval($sales->discount_amount + $discount);
            $sales->save();
        }

        $sms = new Sms();
        $sms->send_sms($sales->customer_contact_number, 'payment_update', $data);

        /*
        $balance = SalesHeader::balance($sales->id);
        if( $balance <= 0 ){
            $this->confirm_order($sales->id, 'Auto confirm after payment completion', Auth::user()->name);
        }
        */
        $total_paid = SalesHeader::paid($sales->id);
        if( $total_paid > 0){
            $this->confirm_order($sales->id, 'Auto confirm after payment completion', Auth::user()->name);
        }


        return back()->with('success','Payment approved with approval code '.$approval_store->approval_code);
    }

    public function confirm(Request $request){
        $this->confirm_order($request->confirm_order_id, $request->confirm_order_remarks, Auth::user()->name);

        ActivityLog::create([
            'created_by' => auth()->id(),
            'activity_type' => 'confirm',
            'dashboard_activity' => 'confirm Sales Transaction',
            'activity_desc' => 'confirmed Sales Transaction with Order Number: '.$request->confirm_order_id.' with remarks: '.$request->confirm_order_remarks,
            'activity_date' => date("Y-m-d H:i:s"),
            'db_table' => 'ecommerce_sales_headers',
            'old_value' => '',
            'new_value' => $request->confirm_order_remarks,
            'reference' => $request->confirm_order_id
        ]);

        return back()->with('success','Order has been confirmed!');
    }

    public function update_delivery_fee(Request $request){
        $update_delivery_fee = SalesHeader::whereId($request->delfee_sales_id)->update([
            'delivery_fee_amount' => $request->delfee
        ]);

        $sales = SalesHeader::whereId($request->delfee_sales_id)->first();
        //logger($sales);
        $dets = $sales->items->sum('gross_amount');

        $new_gross = $dets + $sales->delivery_fee_amount;
        $new_net = ($dets + $sales->delivery_fee_amount) - $sales->discount_amount;

        SalesHeader::whereId($request->delfee_sales_id)->update([
            'gross_amount' => $new_gross,
            'net_amount' => $new_net
        ]);

        if ($sales->payment_status == 'PAID' && $new_net > SalesHeader::paid($sales->id)) {
            SalesHeader::whereId($request->delfee_sales_id)->update([
                'payment_status' => 'PENDING'
            ]);
        }

        //logger(SalesHeader::whereId($request->delfee_sales_id)->first());

        ActivityLog::create([
            'created_by' => auth()->id(),
            'activity_type' => 'insert',
            'dashboard_activity' => 'Added delivery fee',
            'activity_desc' => 'added delivery fee of '.$request->delfee.' to Sales Transaction with Order Number: '.$request->delfee_sales_id,
            'activity_date' => date("Y-m-d H:i:s"),
            'db_table' => 'ecommerce_sales_headers',
            'old_value' => '',
            'new_value' => $request->delfee,
            'reference' => $request->delfee_sales_id
        ]);

        return back()->with('success','Delivery Fee has been updated');
    }

    public function confirm_order($order_id,$remarks,$confirm_by){
        $sales = SalesHeader::whereId($order_id)->first();
        $payment = SalesPayment::where('sales_header_id', $order_id)->latest()->first();
        
        $sales->update([
            'isConfirm' => 1,
            'confirmed_by' => $confirm_by,
            'confirm_remarks' => $remarks,
            'confirmed_on' => date('Y-m-d H:i:s'),
            'payment_status' => $payment && $payment?->payment_type == 'COD' ? 'PAID' : 'UNPAID'
        ]);

        $sh = new SalesHeader();

        if ($sales->order_source == 'Web') {
            $sh->assign_to_production_branch($sales, 1);
        } else {
            $sh->assign_to_production_branch($sales, $sales->temp_prod_branch);
        }

        if ($sales->delivery_status == 'Waiting for Payment' || $sales->delivery_status == '' || is_null($sales->delivery_status)) {
            SalesHeader::whereId($sales->id)->update(['delivery_status' => 'Processing Stock']);
        }
    
        //$mobile = SalesHeader::whereId($order_id)->first();
            
        $sms = new Sms();
        $sms->send_sms($sales->customer_contact_number, 'confirm_order', $sales);
        
        // return true;
    }

    public function disapprove_payment($id){
        $data = SalesPayment::withTrashed()->whereId($id)->first();
        $s = SalesPayment::whereId($id)->update(['status' => 'CANCELLED']);
        $s = SalesPayment::whereId($id)->delete();
        $data = SalesPayment::withTrashed()->whereId($id)->first();

        if(strlen($data->sales->email) > 5){
            Mail::to($data->sales->email)->send(new PaymentDisapprovedAdmin($data));
        }
        $sales = SalesHeader::withTrashed()->whereId($data->sales_header_id)->first();
        $sms = new Sms();
        $sms->send_sms($sales->customer_contact_number, 'payment_update', $data);

        return back()->with('success','Payment has been Cancelled!');
    }

    public function index()
    {
        if (!auth()->user()->has_access_to_route('sales-transaction.index')) {
            return response()->view('components.unauthorize-access');
        }
        
        if (auth()->user()->role_id == config('auth.driver_role_id')) {
            return redirect()->route('sales-transaction.driver_sales_transaction');
        } 

        $showDeleted = request()->boolean('showDeleted') && request()->has('showDeleted') && request()->showDeleted == 'on';

        $showUnread = request()->boolean('unread') && request()->has('unread') && request()->unread == 'on';

        if(auth()->user()->role_id == 4) // branch manager user
            $customConditions = [
                [
                    'field' => 'status',
                    'operator' => '=',
                    'value' => 'active',
                    'apply_to_deleted_data' => true
                ],
                [
                    'field' => 'order_source',
                    'operator' => '=',
                    'value' => session('branch'),
                    'apply_to_deleted_data' => true
                ]
            ];
        else {
            $customConditions = [
                [
                    'field' => 'status',
                    'operator' => '=',
                    'value' => request()->has('order_status') && request()->order_status == 'Abandoned' ? '' : 'active',
                    'apply_to_deleted_data' => true
                ],
            ];
        }
        $today = now();

        $roleId        = auth()->user()->role_id;
        $role          = Role::find($roleId);
        $hasBranches   = (int) $role->has_branches === 1;
        $hasProdBranch = (int) $role->has_production_branch === 1;

        $branches   = UserBranch::accessBranch();
        $locations  = [];
        foreach ($branches as $branch) {
            $locations[] = $branch?->branch?->name ?? $branch?->name ?? null;
        }

        if (auth()->user()->role_id == 1) {
            array_push($locations, 'Web');
        }

        if (in_array('Tandang Sora Head Office', $locations)) {
            array_push($locations, 'Web');
        }

        $isDispatcher = auth()->user()->role_id == 5;

        if(auth()->user()->role_id == 1 || $hasProdBranch || auth()->user()->role_id == 3 || $hasBranches || auth()->user()->role_id == 5){

            if ($hasProdBranch || $hasBranches) {
                $productionBranches = $hasProdBranch
                    ? explode(',', auth()->user()->production_branch_id)
                    : [];

                if (in_array(1, $productionBranches)) {
                    array_push($locations, 'Web');
                }

                // Subquery of eligible sales_header ids
                $eligible = DB::table('ecommerce_sales_details as d')
                    ->join('job_orders as jo', 'jo.sales_detail_id', '=', 'd.id')
                    ->join('production_orders as po', 'po.joborder_id', '=', 'jo.id')
                    ->when(count($productionBranches) > 0, function ($q) use ($productionBranches) {
                        $q->whereIn('po.branch_id', $productionBranches);
                    })
                    ->where('d.delivery_date', '>=', $today->startOfDay()->toDateTimeString())
                    ->select('d.sales_header_id');

                $model = SalesHeader::with(['items' => function ($q) {
                        $q->orderBy('delivery_date', 'asc');
                    }])
                    ->paidOnlyForForecasterRole()
                    ->where('has_sub', 0)
                    ->when($showDeleted === true,
                        fn ($q) => $q->where('for_deletion', 1),
                        fn ($q) => $q->where('for_deletion', 0)
                    )
                    ->when($showUnread === true,
                        fn ($q) => $q->where('is_new_order', 1)
                    )
                    ->when($isDispatcher == true,
                        fn ($q) => $q->where('isConfirm', 1)
                    )
                    // apply production / branch filters without plucking IDs
                    ->where(function ($q) use ($hasProdBranch, $eligible, $hasBranches, $locations) {
                        if ($hasProdBranch) {
                            // this becomes: where id in (select sales_header_id from ...)
                            $q->orWhereIn('id', $eligible);
                        }

                        if ($hasBranches && count($locations) > 0) {
                            $q->orWhere(function ($q2) use ($locations) {
                                $q2->whereIn('outlet', $locations)
                                ->orWhereIn('order_source', $locations)
                                ->orWhereIn('delivery_branch', $locations);
                            });
                        }
                    });
            } elseif (auth()->user()->role_id == 3) {

                $eligible = DB::table('ecommerce_sales_details as d')
                    ->join('job_orders as jo', 'jo.sales_detail_id', '=', 'd.id')
                    ->join('production_orders as po', 'po.joborder_id', '=', 'jo.id')
                    ->where('d.delivery_date', '>=', $today->startOfDay()->toDateTimeString())
                    ->select('d.sales_header_id');

                $model = SalesHeader::with([
                        'items' => function ($q) {
                            $q->orderBy('delivery_date', 'asc');
                        }
                    ])
                    ->paidOnlyForForecasterRole()
                    ->whereIn('id', $eligible) 
                    ->when($showDeleted === true,
                        fn ($q) => $q->where('for_deletion', 1),
                        fn ($q) => $q->where('for_deletion', 0)
                    )
                    ->when($showUnread === true,
                        fn ($q) => $q->where('is_new_order', 1)
                    )
                    ->when($hasBranches && count($locations) > 0,
                        fn ($q) => $q->where(function ($q2) use ($locations) {
                            $q2->whereIn('outlet', $locations)
                            ->orWhereIn('order_source', $locations)
                            ->orWhereIn('delivery_branch', $locations);
                        })
                    );
            } else {
                $model = SalesHeader::where('id','>',0)
                    ->with('items', function($q) use($today) {
                        $q->where('delivery_date', '>=', $today->startOfDay()->toDateTimeString())
                          ->orderBy('delivery_date', 'desc');
                    })
                    ->paidOnlyForForecasterRole()
                    ->where('has_sub', 0)
                    ->when($isDispatcher == true,
                        fn ($q) => $q->where('isConfirm', 1)
                    )
                    ->when($showDeleted === true,
                        fn ($q) => $q->where('for_deletion', 1),
                        fn ($q) => $q->where('for_deletion', 0)
                    )->when($hasBranches && count($locations) > 0,
                        fn ($q) => $q->where(function ($q2) use ($locations) {
                            $q2->whereIn('outlet', $locations)
                               ->orWhereIn('order_source', $locations)
                               ->orWhereIn('delivery_branch', $locations);
                        }),
                        fn ($q) => $q
                    )->when($showUnread === true,
                        fn ($q) => $q->where('is_new_order', 1)
                    );
            }
        }else{
            if ($role->has_branches == 1) {
                $branches = UserBranch::accessBranch();

                $locations = [];
                foreach($branches as $branch){
                    array_push($locations, $branch->branch->name);
                }
                if (auth()->user()->role_id == 1) {
                    array_push($locations, 'Web');
                }

                $model = SalesHeader::where('id','>',0)
                                ->when($showDeleted === true,
                                    fn ($q) => $q->where('for_deletion', 1),
                                    fn ($q) => $q->where('for_deletion', 0)
                                )
                                ->when($showUnread === true,
                                    fn ($q) => $q->where('is_new_order', 1)
                                )
                                ->when($isDispatcher == true,
                                    fn ($q) => $q->where('payment_status', '==', 'PAID')->orWhere('isConfirm', 1)
                                )
                                ->where(function ($query) use($locations) {
                                    $query->whereIn('outlet', $locations)
                                        ->orWhereIn('order_source', $locations)
                                        ->orWhereIn('delivery_branch', $locations);
                                });
            } else {
                $model = SalesHeader::where('id','>',0)
                                ->when($showDeleted === true,
                                    fn ($q) => $q->where('for_deletion', 1),
                                    fn ($q) => $q->where('for_deletion', 0)
                                )
                                ->when($isDispatcher == true,
                                    fn ($q) => $q->where('payment_status', '==', 'PAID')->orWhere('isConfirm', 1)
                                )
                                ->when($showUnread === true,
                                    fn ($q) => $q->where('is_new_order', 1)
                                );
            }
        }
        $model = $this->additional_filters($model);
      
        $selectFields = [
            'id',
            'order_source',
            'delivery_fee_amount',
            'outlet',
            'delivery_branch',
            'delivery_type',
            'for_deletion',
            'contact_person',
            'instruction',
            'customer_delivery_adress',
            'outlet',
            'customer_location',
            'order_number',
            'customer_name',
            'customer_location',
            'isConfirm',
            'created_at',
            'status',
            'delivery_status',
            'payment_status',
            'net_amount',
            'gross_amount',
            'deleted_at',
            DB::raw('(SELECT ecommerce_sales_details.delivery_date From ecommerce_sales_details WHERE ecommerce_sales_headers.id=ecommerce_sales_details.sales_header_id GROUP BY ecommerce_sales_details.sales_header_id) as date_needed')
        ];

        $filterFields = [
            'order_number', 
            'customer_name', 
            'date_needed', 
            'start_date', 
            'end_date'
        ];

        if ($isDispatcher || (auth()->user()->role_id == 3)) {
            $listing = new ListingHelper('desc',20,'date_needed', $customConditions);
        } else {
            $listing = new ListingHelper('desc',20,'order_number', $customConditions);
        }
        $sales = $listing->filter_fields($filterFields)->simple_search_using_collection($model, $this->searchFields,  [],  [], [], $selectFields, $filterFields);

        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search_using_collection';
        //dd($sales);


        return view('admin.sales.index',compact('sales','filter','searchType'));

    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
    
        $sales = SalesHeader::whereIn('id', $ids)
            ->where('isConfirm', '!=', 1)
            ->get();

        foreach ($sales as $sale) {
            $sale->for_deletion = 1;
            $sale->status = 'CANCELLED';
            $sale->save();
        }
    
        return redirect()->back()->with('success', 'Selected sales have been deleted.');
    }

    public function bulkDeleteMixed(Request $request)
    {
        $records = json_decode($request->input('records'), true);

        foreach ($records as $record) {
            if ($record['type'] === 'sales') {
                $sale = SalesHeader::find($record['id']);
                $sale->for_deletion = 1;
                $sale->status = 'CANCELLED';
                $sale->save();

            } elseif ($record['type'] === 'job') {
                JobOrder::find($record['id'])?->delete();
            }
        }

        return redirect()->back()->with('success', 'Selected records deleted successfully.');
    }

    public function additional_filters($model)
    {
        if(isset($_GET['order_source'])){
            $order_sources = request()->get('order_source');
            $model = $model->whereIn('order_source', $order_sources);
        }
        if (isset($_GET['delivery_address'])) {
            $delivery_addresses = request()->get('delivery_address');

            $model = $model->where(function ($q) use ($delivery_addresses) {
                // Door to Door then use delivery_branch
                $q->where(function ($q2) use ($delivery_addresses) {
                    $q2->where('delivery_type', 'Door to door delivery')
                    ->whereIn('delivery_branch', $delivery_addresses);
                })

                // Store Pickup thenuse outlet
                ->orWhere(function ($q2) use ($delivery_addresses) {
                    $q2->where('delivery_type', 'Store Pickup')
                    ->whereIn('outlet', $delivery_addresses);
                });
            });
        }
        if(isset($_GET['order_status']) && strlen($_GET['order_status']) > 0){
            if($_GET['order_status'] == 2){
                $model = $model->where('delivery_status','=','Open Date');        
            }
            elseif ($_GET['order_status'] == 'Cancelled') {
                $model = $model->where('status','=','Cancelled');        
            }
            elseif ($_GET['order_status'] == 'Abandoned') {
                $model = $model->where('status','=','Abandoned');        
            }
            else{
                $model = $model->where('isConfirm','=',$_GET['order_status']);        
            }
        }
        if(isset($_GET['start_date']) && strlen($_GET['start_date']) > 1){
            $model = $model->where('created_at','>=',$_GET['start_date'].' 00:00:00');        
        }
        if(isset($_GET['end_date']) && strlen($_GET['end_date']) > 1){
            $model = $model->where('created_at','<=',$_GET['end_date'].' 23:59:59');        
        }
        if(isset($_GET['dn_start_date']) && strlen($_GET['dn_start_date']) > 1){
            $d = SalesDetail::where('delivery_date','>=',$_GET['dn_start_date'].' 00:00:00')->select('sales_header_id')->get();
            $model = $model->whereIn('id',$d);        
        }
        if(isset($_GET['dn_end_date']) && strlen($_GET['dn_end_date']) > 1){
            $d = SalesDetail::where('delivery_date','<=',$_GET['dn_end_date'].' 23:59:59')->select('sales_header_id')->get();
            $model = $model->whereIn('id',$d);              
        }

        if(isset($_GET['delivery_type']) && strlen($_GET['delivery_type']) > 0){
            $model = $model->where('delivery_type','=',$_GET['delivery_type']);        
        }
        
        if (request('filter') === 'unpaid') {
            $model = $model->unpaid();
        }

        if (request('filter') === 'partial') {
            $model = $model->partial();
        }

        if (request('filter') === 'paid') {
            $model = $model->paid();
        }

        return $model;
    }

    public function sales_list(){

        $sales = SalesHeader::where('user_id',Auth::id())->orderBy('id','desc')->get();

        $page = new Page();
        $page->name = 'Sales Transaction';

        return view('theme.'.config('app.frontend_template').'.pages.ecommerce.sales',compact('sales','page'));
    }

    public function store(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        $sale = SalesHeader::findOrFail($request->id_delete);

        // ActivityLog::create([
        //     'created_by' => auth()->id(),
        //     'activity_type' => 'delete',
        //     'dashboard_activity' => 'delete Sales Transaction',
        //     'activity_desc' => 'deleted Sales Transaction with Order Number: '.$sale->order_number,
        //     'activity_date' => date("Y-m-d H:i:s"),
        //     'db_table' => 'ecommerce_sales_headers',
        //     'old_value' => '',
        //     'new_value' => '',
        //     'reference' => $sale->id
        // ]);

        $sale->for_deletion = 1;
        $sale->status = 'CANCELLED';
        $sale->save();

        return back()->with('success','Successfully deleted transaction');
    }

    public function cancel(Request $request, SalesHeader $salesHeader)
    {
        $salesHeader = SalesHeader::findOrFail($request->id_cancel);

        if ($salesHeader->status == 'CANCELLED') {
            return back()->with('error', 'Transaction is already cancelled');
        }

        if ($salesHeader->status == 'ABANDONED') {
            return back()->with('error', 'Transaction is already abandoned');
        }
        
        if ($salesHeader?->user?->email) {
            Mail::to($salesHeader->user->email)
                ->send(new ManualOrderCancelledByAdminMail($salesHeader));
        }

        // Cancel current
        $salesHeader->status = 'CANCELLED';
        $salesHeader->save();

        if ($salesHeader->is_sub && $salesHeader->parent_sales_header_id) {

            $parent = SalesHeader::find($salesHeader->parent_sales_header_id);

            if ($parent) {

                // Get all children of parent
                $children = SalesHeader::where('parent_sales_header_id', $parent->id)->get();

                // Check if ALL are cancelled or abandoned
                $allClosed = $children->every(function ($child) {
                    return in_array($child->status, ['CANCELLED', 'ABANDONED']);
                });

                if ($allClosed) {

                    // Decide parent status
                    // If ALL cancelled - CANCELLED
                    // Else - ABANDONED
                    $allCancelled = $children->every(function ($child) {
                        return $child->status === 'CANCELLED';
                    });

                    $parent->status = $allCancelled ? 'CANCELLED' : 'ABANDONED';
                    $parent->save();
                }
            }
        }

        return back()->with('success','Transaction has been cancelled');
    }

    public function restore($sales)
    {
        SalesHeader::withTrashed()->findOrFail($sales)->restore();
        SalesHeader::withTrashed()->findOrFail($sales)->update(['for_deletion' => 0]);

        return back()->with('success', 'The transaction has been restored');
    }

    public function update(Request $request)
    {

        $save = SalesPayment::create([
            'sales_header_id' => $request->id,
            'payment_type' => $request->payment_type,
            'amount' => $request->amount,
            'status'  => (isset($request->status) ? 'PAID' : 'UNPAID'),
            'payment_date'  => $request->payment_date,
            'receipt_number'  => $request->receipt_number,
            'created_by' => Auth::id()
        ]);

        $sales = SalesHeader::where('id',$request->id)->first();
        $totalPayment = SalesPayment::where('sales_header_id',$request->id)->where('status', 'PAID')->sum('amount');
        $total = $totalPayment + $request->amount;
        if($total >= $sales->net_amount)
            $status = 'PAID';
        else $status = 'UNPAID';

        $save = SalesHeader::findOrFail($request->id)->update([
            'payment_status' => $status
        ]);

        return redirect()->route('sales-transaction.index')->with('success','Successfully updated payment!');
        //return $status;
    }

    public function show($id)
    {
        $sales = SalesHeader::with(['deliveryAddress', 'couponUsed', 'user', 'items'])->where('id',$id)->first();

        if (!$sales) {
            return redirect()->route('sales-transaction.index')->with('error', 'Sales record not found.');
        }

        // if ($sales->is_sub == 1) {
        //     $subSales = SalesHeader::where('id', $sales->parent_sales_header_id)->first();
        //     $salesPayments = $subSales->payments ?? collect();
        //     $totalPayment = SalesPayment::where('sales_header_id', $sales->parent_sales_header_id)->sum('amount');
        // } else {
        //     $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        //     $totalPayment = SalesPayment::where('sales_header_id',$id)->sum('amount');
        // }

        $salesPayments = SalesPayment::where('sales_header_id',$id)->where('status', '!=', 'CANCELLED')->get();
        $totalPayment = SalesPayment::where('sales_header_id',$id)->where('status', 'PAID')->sum('amount');

        if (!$sales) {
            return redirect()->route('sales-transaction.index')->with('error', 'Sales record not found.');
        }
        

        $gc = GiftCertificate::where('sales_header_id',$id)->get();
        $salesDetails = SalesDetail::where('sales_header_id',$id)->get();
        $deliveries = DeliveryStatus::where('order_id',$id)->get();
        $deliveries = DeliveryStatus::where('order_id',$id)->get();
        $totalNet = ($sales->items->sum('net_amount') + $sales->delivery_fee_amount) - $sales->discount_amount;

        if($totalNet <= $totalPayment){
            $status = 'PAID';
        }
        else {
            $status = 'UNPAID';
            if($totalPayment > 0){
                $status = 'PARTIAL';
            }
        }

        if (auth()->user()->role_id == 15) {
            $sales->has_transited = 0;
            $sales->save();
        } else {
            $sales->is_new_order = 0;
            $sales->save();
        }

        return view('admin.sales.view',compact('sales','salesPayments','salesDetails','status','deliveries','gc'));
    }
    
    public function update_sales_details($id)
    {
        $salesdetail = SalesDetail::where('sales_header_id',$id)->first();
        $salesheader = SalesHeader::with('deliveryAddress')->find($id);

        if (
            auth()->user()->has_access_to_route('sales-transaction.update') &&
            $salesheader->isConfirmedAndPastCutoffAndForecasted()) {
                return redirect()->route('sales-transaction.index')->with('error', 'Confirmed orders past cutoff and forecasted cannot be updated.');
        }

        $products = Product::orderBy('name')->get();

        $dateneeded = '';
        $date_only = '';
        $time_only = '';
        $locationed = '';
        if(!empty($salesdetail)){
            $date_only = date('Y-m-d',strtotime($salesdetail->delivery_date));
            $time_only = date('H:i',strtotime($salesdetail->delivery_date));
            $dateneeded = Carbon::parse($salesdetail->delivery_date)->addDays(2)->format('Y-m-d h:i A');
        }

        // dd($dateneeded);

        if($salesheader->delivery_type == 'Door to door delivery'){
            $locationed = $salesheader->customer_location;
        }
        if($salesheader->delivery_type == 'Store Pickup'){
            $locationed = $salesheader->outlet;
        }

        $branches_store = Branch::where('status', 1)->orderBy('name','asc')->get();

        // dd($salesheader->deliveryAddress);

        $locations = Deliverablecities::distinct()->where('is_active', 1)->orderBy('name')->get(['name']);

        $locations = $locations->filter(function ($value) {
            return !is_null($value->name) && $value->name !== '';
        })->values();
      
        // ActivityLog::create([
        //     'created_by' => auth()->id(),
        //     'activity_type' => 'update',
        //     'dashboard_activity' => 'update Sales Details',
        //     'activity_desc' => 'updated Sales Details with Order Number: '.$salesheader?->order_number,
        //     'activity_date' => date("Y-m-d H:i:s"),
        //     'db_table' => 'ecommerce_sales_details',
        //     'old_value' => '',
        //     'new_value' => '',
        //     'reference' => $salesdetail?->id
        // ]);

        $provinces = Deliverablecities::query()
            ->select('province')
            ->where('is_active', 1)
            ->whereNotNull('province')->where('province', '!=', '')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');

        $cities = Deliverablecities::query()
            ->select('city')
            ->where('is_active', 1)
            ->whereNotNull('city')->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

            // dd($salesheader);

        // return view('admin.sales.update_sales_detail',compact('salesheader','dateneeded','date_only','time_only','locationed','products','branches_store', 'locations', 'provinces', 'cities'));

        return view('admin.sales.update-sales-details', [
            'salesheader' => $salesheader,
            'dateneeded' => $dateneeded,
            'date_only' => $date_only,
            'time_only' => $time_only,
            'locationed' => $locationed,
            'products' => $products,
            'branches_store' => $branches_store,
            'locations' => $locations,
            'provinces' => $provinces,
            'cities' => $cities
        ]);

    }

    public function quick_update(Request $request)
    {
        $update = SalesHeader::findOrFail($request->pages)->update([
            'delivery_status' => $request->status
        ]);

        $order = SalesHeader::findOrFail($request->pages);
        //dd($order);
        $this->sms_update_order_status($order->customer_contact_number,$order);

        return back()->with('success','Successfully updated delivery status!');

    }

    public function delivery_status(Request $request)
    {
        // dd($request->all());
        $type = $request->has('type') ? $request->type : 'sales';

        $data = [
            'user_id' => Auth::id(),
            'status' => $request->delivery_status,
            'remarks' => $request->del_remarks,
            'delivered_by' => $request->delivered_by,
        ];

        if ($type === 'joborder') {

            $data['job_order_id'] = $request->del_id;
            $data['type'] = 'joborder';

            JobOrder::whereId($request->del_id)->update([
                'delivery_status' => $request->delivery_status
            ]);

            $update_delivery_table = DeliveryStatus::create($data);

            if ($request->hasFile('image')) {
                $dest = public_path('images/proof-of-delivery');

                if (!is_dir($dest)) {
                    mkdir($dest, 0755, true);
                }

                foreach ($request->file('image') as $file) {
                    $ext  = $file->getClientOriginalExtension();
                    $name = now()->format('YmdHis') . '-' . Str::random(8) . '.' . $ext;
                    $file->move($dest, $name);

                    DeliveriesImage::create([
                        'delivery_status_id' => $update_delivery_table->id,
                        'image' => $name,
                        'user_id' => Auth::id() ?? null
                    ]);
                }
            }

            if(($request->delivery_status == 'Ready For delivery' || $request->delivery_status == 'Delivered/Picked Up' || $request->delivery_status == 'In Transit')){
                if ($request->delivery_status == 'In Transit') {
                    $joborder = JobOrder::where('id', $request->del_id)->first();
                    $salesDetail = SalesDetail::where('id', $joborder->sales_detail_id)->first();
                    if (!$salesDetail) {
                        return back()->with('error', 'Sales detail not found for the job order.');
                    }
                    $order =  SalesHeader::where('id', $salesDetail->sales_header_id)->with('deliveryAddress', 'items', 'couponUsed', 'user')->first();

                    $driver = User::where('id', $request->delivered_by)->first();

                    if ($driver && !empty($driver->email)) {
                        Mail::to($driver->email)->send(new DeliveryAssignedMail($order, $driver));
                    }

                    $sms = new Sms();
                    if ($driver && $driver->contact_mobile) {
                        $sms->send_sms($driver->contact_mobile, 'delivery_assigned', $order, $driver);
                    }
                }
            }
        } else {
            if ($request->has('deliveries_lists') && !empty($request->deliveries_lists)) {
                $deliveryAddress = ProductDeliveryAddress::where('id', $request->deliveries_lists)->first();
                if ($deliveryAddress) {
                    $deliveryAddress->delivery_status = $request->delivery_status;
                    $deliveryAddress->save();
                }
            } else {
                SalesHeader::whereId($request->del_id)->update([
                    'delivery_status' => $request->delivery_status
                ]);
            }

            $data['order_id'] = $request->del_id;
            $data['type'] = 'sales';

            $update_delivery_table = DeliveryStatus::create($data);

            if ($request->hasFile('image')) {
                $dest = public_path('images/proof-of-delivery');

                if (!is_dir($dest)) {
                    mkdir($dest, 0755, true);
                }

                foreach ($request->file('image') as $file) {
                    $ext  = $file->getClientOriginalExtension();
                    $name = now()->format('YmdHis') . '-' . Str::random(8) . '.' . $ext;
                    $file->move($dest, $name);

                    DeliveriesImage::create([
                        'delivery_status_id' => $update_delivery_table->id,
                        'image' => $name,
                        'user_id' => Auth::id() ?? null
                    ]);
                }
            }

            if(!empty($update_delivery_table->sales->email)){
                Mail::to($update_delivery_table->sales->email)->send(new DeliveryMovement($update_delivery_table));
            }

            $sms = new Sms();
            $order =  SalesHeader::where('id',$request->del_id)->with('deliveryAddress', 'items', 'couponUsed', 'user')->first();
            $order->is_new_order = 0;
            $order->save();
            if(($request->delivery_status == 'Ready For delivery' || $request->delivery_status == 'Delivered/Picked Up' || $request->delivery_status == 'In Transit')){
                
                if ($order?->customer_contact_number) {
                    $sms->send_sms($order->customer_contact_number, 'delivery_update', $order);
                }

                if ($request->delivery_status == 'In Transit') {
                    $order->has_transited = 1;
                    $order->save();
                    $driver = User::where('id', $request->delivered_by)->first();

                    if ($driver && !empty($driver->email)) {
                        if ($request->has('deliveries_lists') && !empty($request->deliveries_lists)) {
                            $deliveryAddress = ProductDeliveryAddress::where('id', $request->deliveries_lists)->first();
                            Mail::to($driver->email)->send(new DeliveryAssignedMultipleMail($order, $driver, $deliveryAddress));
                        } else {
                            Mail::to($driver->email)->send(new DeliveryAssignedMail($order, $driver));
                        }
                    }
                    if ($driver && $driver->contact_mobile) {
                        $sms->send_sms($driver->contact_mobile, 'delivery_assigned', $order, $driver);
                    }
                }
            }
            if ($order?->customer_contact_number) {
                $this->sms_send_order_status($order?->customer_contact_number, $order);
            }
        }

        ActivityLog::create([
            'created_by' => auth()->id(),
            'activity_type' => 'update',
            'dashboard_activity' => 'created new delivery',
            'activity_desc' => 'created new delivery status',
            'activity_date' => date("Y-m-d H:i:s"),
            'db_table' => 'ecommerce_delivery_status',
            'old_value' => '',
            'new_value' => $request->delivery_status,
            'reference' => $update_delivery_table->id
        ]);

        if (isset($request->driver) && $request->driver) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return back()->with('success','Successfully updated delivery status!');
        }
    }

    public function showDeliveryStatus($id)
    {
        $status = DeliveryStatus::with('images')->where('order_id', $id)->orWhere('job_order_id', $id)->latest()->first();
        $deliveries = ProductDeliveryAddress::where('sales_header_id', $id)->get();

        return response()->json([
            'status' => $status,
            'deliveries' => $deliveries
        ]);
    }

    public function sms_send_order_status($number, $order){

		$name = $order->user->name;
		$orderNumber = $order->order_number;
        $receiver = $number;

		try {
			$message = "Hi $name. Your order #$orderNumber is now on ".strtoupper($order->delivery_status)." status -LydiasLechon";
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://api.wavecell.com/sms/v1/Lydia_MKT/single');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");

			$headers = array();
			$headers[] = 'Authorization: Bearer ' . config('services.sms.api_key');
			$headers[] = 'Content-Type: application/json';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
			    //echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
		} catch (\Exception $e) {
			logger()->error('SMS Error: '.$e->getMessage());
		}
    }

    public function sms_update_order_status($number,$order){

        $message = "Your order #".$order->order_number." is now on ".strtoupper($order->delivery_status)." status -LydiasLechon";
        $apicode = "TR-JUNDR725076_39D3A";
        $url = 'https://www.itexmo.com/php_api/api.php';
        $itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
        $param = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($itexmo),
            ),
        );
        $context  = stream_context_create($param);
       // return;
        return file_get_contents($url, false, $context);
    }

    public function view_payment($id)
    {
        $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        $totalPayment = SalesPayment::where('sales_header_id',$id)->sum('amount');
        $totalNet = SalesHeader::where('id',$id)->sum('net_amount');
        $remainingPayment = $totalNet - $totalPayment;

        return view('admin.sales.payment',compact('salesPayments','totalPayment','totalNet','remainingPayment'));
    }

    public function cancel_product(Request $request)
    {
        return $request;
    }

    public function payment_add_store(Request $request)
    {
        $image_url = '';
        if($request->hasFile('payment_attachment'))
        {
            $newFile = $this->upload_file_to_storage('payments', $request->file('payment_attachment'));
            $image_url = $newFile['url'];
        }
        $sales = SalesHeader::whereId($request->sales_header_id)->first();
        $payment = SalesPayment::create([
            'sales_header_id' => $request->sales_header_id,
            'payment_type' => $request->pamenty_mode,
            'amount' => $request->amount,
            'status' => 'PENDING',
            'payment_date' => $request->payment_dt,
            'receipt_number' => $request->ref_no,
            'file_url' => $image_url,
            'remark' => $request->payment_remark,
            'order_number' => $sales->order_number,
            'created_by' => Auth::id()
        ]);
        

        $balance = SalesHeader::balance($sales->id);
        if( $balance <= 0 ){
            $this->confirm_order($sales->id, 'Auto confirm after payment completion', Auth::user()->name);
        }

        ActivityLog::create([
            'created_by' => auth()->id(),
            'activity_type' => 'insert',
            'dashboard_activity' => 'Added new payment',
            'activity_desc' => 'added new payment for order #'.$sales->order_number.' with amount of '.$request->amount,
            'activity_date' => date("Y-m-d H:i:s"),
            'db_table' => 'ecommerce_sales_payments',
            'old_value' => '',
            'new_value' => '',
            'reference' => $payment->id
        ]);

        $sms = new Sms();
        $sms->send_sms($sales->customer_contact_number, 'payment_new', $payment);
        return back()->with('success','Payment has been added successfully.');
    }

    public function payment_add_store_customer(Request $request)
    {


        $pay_mode = $request->pamenty_mode;

        if($request->pamenty_mode == 'BDO'){
            $pay_mode = 'Bank Deposit';
        }
        if($request->pamenty_mode == 'Debit/Credit Card'){
            $pay_mode = 'Credit/Debit Card';
        }
        if($request->pamenty_mode == 'Metrobank'){
            $pay_mode = 'Bank Deposit';
        }



        $image_url = '';
        if($request->hasFile('file'))
        {
            $newFile = $this->upload_file_to_storage('payments', $request->file('file'));
            $image_url = $newFile['url'];
        }

        if (auth()->guest()) {
            $user = User::find(9999);
            if (empty($user)) {
                $user = $this->create_guest_account();
            }
        } else {
            $user = auth()->user();
        }

        $s = SalesHeader::where('order_number',$request->sales_header_id)->first();
        $check_if_exist = SalesPayment::where('sales_header_id', $s->id)
                    ->where('payment_type', $pay_mode)
                    ->where('receipt_number', $request->ref_no)
                    ->where('payment_date', $request->payment_dt)
                    ->where('amount', $request->amount)
                    ->count();
        if($check_if_exist < 1){
            $payment_saved = SalesPayment::create([
                'sales_header_id' => $s->id,
                'payment_type' => $pay_mode,
                'amount' => $request->amount,
                'status' => 'PENDING',
                'payment_date' => $request->payment_dt,
                'order_number' => $request->sales_header_id,
                'receipt_number' => $request->ref_no,
                'file_url' => $image_url,
                'created_by' => $user->id
            ]);
            Mail::to(env('EMAIL_ADMIN'))->send(new PaymentSubmittedAdmin($payment_saved));
        }
        
          //dd($request);
        return redirect('/order?success=order_submitted');
    }

    public function upload_file_to_storage($folder, $file, $key = '') {

        $fileName = $file->getClientOriginalName();
        if (Storage::disk('public')->exists($folder.'/'.$fileName)) {
            $fileNames = explode(".", $fileName);
            $count = 2;
            $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
            while(Storage::disk('public')->exists($folder.'/'.$newFilename)) {
                $count += 1;
                $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
            }

            $fileName = $newFilename;
        }

        $path = Storage::disk('public')->putFileAs($folder, $file, $fileName);
        $url = Storage::disk('public')->url($path);
        $returnArr = [
            'name' => $fileName,
            'url' => $url
        ];

        if ($key == '') {
            return $returnArr;
        } else {
            return $returnArr[$key];
        }
    }

    public function display_payments(Request $request){
        $input = $request->all();

        $sale = SalesHeader::where('id', $request->id)->first();

        // if (isset($sale->is_sub) && $sale->is_sub == 1) {
        //     $parentSale = SalesHeader::where('id', $sale->parent_sales_header_id)->first();
        //     $payments = SalesPayment::where('sales_header_id', $parentSale->id)->where('status', '!=', 'CANCELLED')->get();
        // } else {
        //     $payments = SalesPayment::where('sales_header_id',$request->id)->where('status', '!=', 'CANCELLED')->get();
        // }

        $payments = SalesPayment::where('sales_header_id',$request->id)->where('status', '!=', 'CANCELLED')->get();

        return view('admin.sales.added-payments-result',compact('payments'));
    }


    public function display_delivery(Request $request){

        $input = $request->all();

        $type = $request->has('type') ? $request->type : 'sales';

        $isDriver = $request->has('user') && $request->user == 'driver';

        $user = auth()->check() ? auth()->user() : null;

        if ($type === 'job') {
            $delivery = DeliveryStatus::with('images')->where('job_order_id',$request->id)->where('type', 'joborder')
                ->when($isDriver && $user, function ($query) use ($user) {
                    $query->where('delivered_by', $user->id)->orWhere('delivered_by', $user->name);
                })
                ->get();
        } else {
            $delivery = DeliveryStatus::with('images')->where('order_id',$request->id)->where('type', 'sales')
                ->when($isDriver && $user, function ($query) use ($user) {
                    $query->where('delivered_by', $user->id)->orWhere('delivered_by', $user->name);
                })->get();
        }

        return view('admin.sales.delivery_history',compact('delivery', 'isDriver'));
    }
    
    public function sales_printout($id)
    {
        $id = base64_decode($id);

        $title = 'Sales Transaction Summary';
        
        $sales = \App\EcommerceModel\SalesHeader::with('couponUsed')->where('id',$id)->first();

        if ($sales->is_sub == 1) {
            $subSales = SalesHeader::where('id', $sales->parent_sales_header_id)->first();
            $salesPayments = $subSales->payments->where('status', 'PAID') ?? collect();
        } else {
            $salesPayments = SalesPayment::where('sales_header_id',$id)->where('status', 'PAID')->get();
        }

        if (!$sales) {
            return redirect()->route('sales-transaction.index')->with('error', 'Sales record not found.');
        }

        $salesDetails  = SalesDetail::where('sales_header_id',$id)->get();
        $deliveries    = DeliveryStatus::where('order_id',$id)->get();

        $gc = GiftCertificate::where('sales_header_id',$id)->get();

        return view('admin.sales.print',compact('sales','salesPayments','salesDetails','deliveries','gc', 'title'));
    }
    
    public function sales_printout_delivery($id)
    {
        $id = base64_decode($id);

        $title = 'Delivery Report';
        
        $sales = \App\EcommerceModel\SalesHeader::with('couponUsed')->where('id',$id)->first();

        if ($sales->is_sub == 1) {
            $subSales = SalesHeader::where('id', $sales->parent_sales_header_id)->first();
            $salesPayments = $subSales->payments->where('status', 'PAID') ?? collect();
        } else {
            $salesPayments = SalesPayment::where('sales_header_id',$id)->where('status', 'PAID')->get();
        }

        if (!$sales) {
            return redirect()->route('sales-transaction.index')->with('error', 'Sales record not found.');
        }

        $salesDetails  = SalesDetail::where('sales_header_id',$id)->get();
        $deliveries    = DeliveryStatus::where('order_id',$id)->get();

        $gc = GiftCertificate::where('sales_header_id',$id)->get();

        return view('admin.sales.print',compact('sales','salesPayments','salesDetails','deliveries','gc', 'title'));
    }
    
    public function sales_printout_driver($id)
    {
        $id = base64_decode($id);

        $title = 'Delivery Report';
        
        $sales = \App\EcommerceModel\SalesHeader::with('couponUsed')->where('id',$id)->first();

        if ($sales->is_sub == 1) {
            $subSales = SalesHeader::where('id', $sales->parent_sales_header_id)->first();
            $salesPayments = $subSales->payments->where('status', 'PAID') ?? collect();
        } else {
            $salesPayments = SalesPayment::where('sales_header_id',$id)->where('status', 'PAID')->get();
        }

        if (!$sales) {
            return redirect()->route('sales-transaction.index')->with('error', 'Sales record not found.');
        }

        $salesDetails  = SalesDetail::where('sales_header_id',$id)->get();
        $deliveries    = DeliveryStatus::where('order_id',$id)->get();

        $gc = GiftCertificate::where('sales_header_id',$id)->get();

        $noHistory = true;

        return view('admin.sales.print',compact('sales','salesPayments','salesDetails','deliveries','gc', 'title', 'noHistory'));
    }
    
    public function update_delivery_branch(Request $request)
    {
        //
    }

    public function payments()
    {
        $payments = SalesPayment::with('approval.user')
                                ->where('sales_header_id','>',0)
                                ->orderBy('id','desc');

        if(isset($_GET['status']) && $_GET['status']){
            $payments = $payments->where('status',$_GET['status']);
        }
        if(isset($_GET['start_date']) && $_GET['start_date']){
            $payments = $payments->where('payment_date','>=',$_GET['start_date']);
        }
        if(isset($_GET['end_date']) && $_GET['end_date']){
            $payments = $payments->where('payment_date','<=',$_GET['end_date']);
        }
        if(isset($_GET['search']) && $_GET['search']){
            $se = (int) $_GET['search'];
           
            $payments = $payments->where('sales_header_id','like','%'.$se.'%');
        }
        $payments = $payments->withTrashed()->paginate(25);

        $page = new Page();
        $page->name = 'Payments';

        $legacyApprovals = \App\Models\Approvals::whereNull('payment_id')
            ->where('approval_type','Payment')
            ->orderBy('created_at')
            ->get()
            ->groupBy('reference_id');

        return view('admin.sales.payments',compact('payments'));

    }

    public function driver_sales_transaction(Request $request)
    {
        
        if(auth()->user()->role_id == 4) // branch manager user
            $customConditions = [
                [
                    'field' => 'status',
                    'operator' => '=',
                    'value' => 'active',
                    'apply_to_deleted_data' => true
                ],
                [
                    'field' => 'order_source',
                    'operator' => '=',
                    'value' => session('branch'),
                    'apply_to_deleted_data' => true
                ]
            ];
        else {
            $customConditions = [
                [
                    'field' => 'status',
                    'operator' => '=',
                    'value' => 'active',
                    'apply_to_deleted_data' => true
                ],
            ];
        }
        if(auth()->user()->role_id == config('auth.driver_role_id')){
            $userName = Auth::user()->id;

            // check if theres a param view in the url and its value is desktop if it doesnt hvae redirect to return redirect()->route('driver.home');

            if (!request()->has('view') || request()->get('view') != 'desktop') {
                return redirect()->route('driver.home');
            }

        // Step 1: SalesHeader
        $salesHeaders = SalesHeader::with(['user', 'items', 'deliveryAddress', 'deliveryStatuses'])
            ->whereHas('deliveryStatuses', function ($q) use ($userName) {
                $q->where('delivered_by', $userName)
                ->whereIn('delivery_status', ['In Transit', 'Returned/Rejected', 'Delivered/Picked Up']);
            })
            ->where('delivery_type', '!=', 'Store Pickup')
            ->get()
            ->map(function ($sale) {
                return [
                    'type' => 'sales',
                    'id' => $sale->id,
                    'delivery_status' => optional($sale->deliveryStatuses->last())->status,
                    'status' => $sale->status,
                    'customer_name' => $sale->customer_name,
                    'date_needed' => optional($sale->items->first())->delivery_date,
                    'qty' => optional($sale->items->first())->qty,
                    'product_id' => optional($sale->items->first())->product_id,
                    'price' => optional($sale->items->first())->price,
                    'delivery_type' => $sale->delivery_type,
                    'trashed' => $sale->trashed() ? true : false,
                    'order_number' => $sale->order_number,
                    'created_at' => $sale->created_at,
                    'order_source' => $sale->order_source,
                    'isConfirm' => $sale->isConfirm,
                    'gross_amount' => $sale->gross_amount,
                    'delivery_address' => $sale->deliveryAddress,
                    'contact_person' => $sale->contact_person,
                ];
            });

        // Step 2: JobOrders
        $jobOrderIds = DeliveryStatus::where('delivered_by', $userName)
            ->whereNotNull('job_order_id')
            ->distinct()
            ->pluck('job_order_id');

        $jobOrders = JobOrder::with('deliveryStatuses')
            ->whereIn('id', $jobOrderIds)
            ->get()
            ->map(function ($job) {
                return [
                    'type' => 'job',
                    'id' => $job->id,
                    'delivery_status' => optional($job->deliveryStatuses->last())->status,
                    'status' => $job->status,
                    'customer_name' => $job->customer_name,
                    'date_needed' => $job->date_needed,
                    'qty' => $job->qty,
                    'product_id' => $job->product_id,
                    'price' => $job->price,
                    'delivery_type' => $job->delivery_method,
                    'trashed' => $job->trashed() ? true : false,
                    'order_number' => $job->jo_number,
                    'created_at' => $job->created_at,
                    'order_source' => 'NA',
                    'isConfirm' => null,
                    'gross_amount' => ($job->price * $job->qty) + ($job->paella_price * $job->paella_qty),
                    'delivery_address' => [],
                ];
            });

        // Step 3: Merge collections
        $merged = collect()->merge($salesHeaders)->merge($jobOrders);

        // Step 4: Apply filters
        $search      = request()->get('search');
        $deliveryType = request()->get('delivery_type');
        $startDate   = request()->get('start_date');
        $endDate     = request()->get('end_date');
        $orderBy     = request()->get('orderBy', 'date_needed');
        $sortBy      = request()->get('sortBy', 'desc');
        $dateneededStart  = request()->get('dn_start_date');
        $dateneededEnd = request()->get('dn_end_date');
        $perPage     = 20;

        // Apply search
        if ($search) {
            $merged = $merged->filter(function ($item) use ($search) {
                return Str::contains($item['order_number'], $search);
            });
        }

        // Apply delivery_type filter
        if ($deliveryType) {
            $merged = $merged->where('delivery_type', $deliveryType);
        }

        // Apply date range filter
        if ($startDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $merged = $merged->filter(fn ($item) =>
                Carbon::parse($item['created_at'])->gte($start)
            );
        }

        if ($endDate) {
            $end = Carbon::parse($endDate)->addDay()->startOfDay();
            $merged = $merged->filter(fn ($item) =>
                Carbon::parse($item['created_at'])->lt($end)
            );
        }

        // Apply date needed range filter
        if ($dateneededStart) {
            $start = Carbon::parse($dateneededStart)->startOfDay();
            $merged = $merged->filter(fn ($item) =>
                Carbon::parse($item['date_needed'])->gte($start)
            );
        }

        if ($dateneededEnd) {
            $end = Carbon::parse($dateneededEnd)->addDay()->startOfDay();
            $merged = $merged->filter(fn ($item) =>
                Carbon::parse($item['date_needed'])->lt($end)
            );
        }

        // Sort by requested column
        $merged = $sortBy === 'desc'
            ? $merged->sortByDesc($orderBy)
            : $merged->sortBy($orderBy);

        // Paginate manually
        $page = request()->get('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $merged->forPage($page, $perPage)->values(),
            $merged->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Return paginated to view
        $sales = $paginated;
        }

        // dd($model);

        // $model = $this->additional_filters($model);
      
        //dd($model->get());


        $filterFields = ['order_number', 'customer_name', 'date_needed'];
        $listing = new ListingHelper('desc',20, 'order_number', $customConditions);

        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search_using_collection';
        //dd($sales);


        return view('admin.sales.driver_index',compact('sales','filter','searchType'));

    }

    public function pending_deletion()
    {
        if (auth()->user()->role_id == config('auth.driver_role_id')) {
            return redirect()->route('sales-transaction.driver_sales_transaction');
        } 

        if(auth()->user()->role_id == 4) // branch manager user
            $customConditions = [
                [
                    'field' => 'status',
                    'operator' => '=',
                    'value' => 'active',
                    'apply_to_deleted_data' => true
                ],
                [
                    'field' => 'order_source',
                    'operator' => '=',
                    'value' => session('branch'),
                    'apply_to_deleted_data' => true
                ]
            ];
        else {
            $customConditions = [
                [
                    'field' => 'status',
                    'operator' => '=',
                    'value' => 'active',
                    'apply_to_deleted_data' => true
                ],
            ];
        }
        $today = now();
        
        $roleId        = auth()->user()->role_id;
        $role          = Role::find($roleId);
        $hasBranches   = (int) $role->has_branches === 1;
        $hasProdBranch = (int) $role->has_production_branch === 1;

        $branches   = UserBranch::accessBranch();
        $locations  = [];
        foreach ($branches as $branch) {
            $locations[] = $branch?->branch?->name ?? $branch?->name ?? null;
        }
        
        if (auth()->user()->role_id == 1) {
            array_push($locations, 'Web');
        }

        if (in_array('Tandang Sora Head Office', $locations)) {
            array_push($locations, 'Web');
        }

        $isDispatcher = auth()->user()->role_id == 5; // dispatcher role

        if (auth()->user()->role_id == 1 || $hasProdBranch || auth()->user()->role_id == 3 || $hasBranches || $isDispatcher) {

            if ($hasProdBranch || $hasBranches) {
                $productionBranches = $hasProdBranch
                    ? explode(',', auth()->user()->production_branch_id)
                    : [];

                if (in_array(1, $productionBranches)) {
                    array_push($locations, 'Web');
                }

                $eligible = DB::table('ecommerce_sales_details as d')
                    ->join('job_orders as jo', 'jo.sales_detail_id', '=', 'd.id')
                    ->join('production_orders as po', 'po.joborder_id', '=', 'jo.id')
                    ->when(count($productionBranches) > 0, function ($query) use ($productionBranches) {
                        $query->whereIn('po.branch_id', $productionBranches);
                    })
                    ->where('d.delivery_date', '>=', $today->startOfDay()->toDateTimeString())
                    ->select('d.sales_header_id');

                $model = SalesHeader::with(['items' => function ($q) {
                        $q->orderBy('delivery_date', 'asc');
                    }])
                    ->whereIn('id', $eligible)
                    ->where('has_sub', 0)
                    ->where('for_deletion', 1)
                    ->when($hasBranches && count($locations) > 0, function ($q) use ($locations) {
                        $q->where(function ($subQ) use ($locations) {
                            $subQ->whereIn('outlet', $locations)
                                ->orWhereIn('order_source', $locations)
                                ->orWhereIn('delivery_branch', $locations);
                        });
                    })
                    ->when($isDispatcher == true,
                        fn ($q) => $q->where('payment_status', '==', 'PAID')->orWhere('isConfirm', 1)
                    )
                    ->orderBy('order_number', 'desc');

            } elseif (auth()->user()->role_id == 3) {

                $eligible = DB::table('ecommerce_sales_details as d')
                    ->join('job_orders as jo', 'jo.sales_detail_id', '=', 'd.id')
                    ->join('production_orders as po', 'po.joborder_id', '=', 'jo.id')
                    ->where('d.delivery_date', '>=', $today->startOfDay()->toDateTimeString())
                    ->select('d.sales_header_id');

                $model = SalesHeader::with(['items' => function ($q) {
                        $q->orderBy('delivery_date', 'asc');
                    }])
                    ->whereIn('id', $eligible)       // still a subquery
                    ->where('has_sub', 0)
                    ->where('payment_status', '!=', 'PENDING')
                    ->where('for_deletion', 1)
                    ->when($hasBranches && count($locations) > 0, function ($q) use ($locations) {
                        $q->where(function ($subQ) use ($locations) {
                            $subQ->whereIn('outlet', $locations)
                                ->orWhereIn('order_source', $locations)
                                ->orWhereIn('delivery_branch', $locations);
                        });
                    });

            } else {
                $model = SalesHeader::with(['items' => function ($q) {
                        $q->orderBy('delivery_date', 'asc');
                    }])
                    ->when($isDispatcher == true,
                        fn ($q) => $q->where('payment_status', '==', 'PAID')->orWhere('isConfirm', 1)
                    )
                    ->where('id', '>', 0)
                    ->where('has_sub', 0)
                    ->where('for_deletion', 1);
            }

        } else {
            $branches = UserBranch::accessBranch();

            $locations = [];
            foreach ($branches as $branch) {
                $locations[] = $branch?->branch?->name ?? $branch?->name;
            }

            if (auth()->user()->role_id == 1) {
                $locations[] = 'Web';
            }

            $model = SalesHeader::with(['items' => function ($q) {
                    $q->orderBy('delivery_date', 'asc');
                }])
                ->where('id', '>', 0)
                ->where('for_deletion', 1)
                ->when($isDispatcher == true,
                    fn ($q) => $q->where('payment_status', '==', 'PAID')->orWhere('isConfirm', 1)
                )
                ->where(function ($query) use ($locations) {
                    $query->whereIn('outlet', $locations)
                        ->orWhereIn('order_source', $locations)
                        ->orWhereIn('delivery_branch', $locations);
                });
        }

        $model = $this->additional_filters($model);
      

        $selectFields = ['id','order_source','delivery_fee_amount','delivery_type','for_deletion','contact_person','instruction','customer_delivery_adress','outlet','customer_location','order_number', 'customer_name', 'customer_location', 'isConfirm', 'created_at', 'status', 'delivery_status', 'payment_status', 'net_amount', 'gross_amount','deleted_at', DB::raw('(SELECT ecommerce_sales_details.delivery_date From ecommerce_sales_details WHERE ecommerce_sales_headers.id=ecommerce_sales_details.sales_header_id GROUP BY ecommerce_sales_details.sales_header_id) as date_needed')];

        $filterFields = ['order_number', 'customer_name', 'date_needed', 'start_date', 'end_date'];
        $listing = new ListingHelper('desc',20,'order_number', $customConditions);
        $sales = $listing->filter_fields($filterFields)->simple_search_using_collection($model, $this->searchFields,  [],  [], [], $selectFields, $filterFields);

        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search_using_collection';
        //dd($sales);


        return view('admin.sales.index-for-deletion',compact('sales','filter','searchType'));

    }

    public function for_deletion(Request $request)
    {
        $id = $request->id;

        if (!auth()->user()->has_access_to_route('sales-transaction.for_deletion')) {
            return back()->with('error', 'You do not have permission to perform this action.');
        }

        $sale = SalesHeader::findOrFail($id);

        ActivityLog::create([
            'created_by' => auth()->id(),
            'activity_type' => 'delete',
            'dashboard_activity' => 'permanently delete Sales Transaction',
            'activity_desc' => 'permanently deleted Sales Transaction with Order Number: '.$sale->order_number,
            'activity_date' => date("Y-m-d H:i:s"),
            'db_table' => 'ecommerce_sales_headers',
            'old_value' => '',
            'new_value' => '',
            'reference' => $sale->id
        ]);

        $sale->delete();

        return back()->with('success','Successfully deleted transaction');
    }
}
