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
use App\Models\ActivityLog;
use App\Models\ProductDeliveryAddress;
use App\Models\UserBranch;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class SalesController extends Controller
{
    private $searchFields = ['order_number','customer_name'];

    public function __construct()
    {
        Permission::module_init($this, 'sales_transaction');
    }

    public function edit_items(){
        $items = SalesDetail::where('sales_header_id',$_GET['id'])->get();
        $products = Product::orderBy('name')->get();
        return view('admin.sales.update_items',compact('items','products'));
    }

    public function update_items(Request $request){
        $head = SalesHeader::whereId($request->ui_sales_id)->first();
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
                $update = SalesDetail::whereId($item->id)->update([
                    'paella_price' => $paella_price,
                    'paella_qty' => $paella_qty,
                    'qty' => $request->input('uiu_qty'.$item->id),
                    'gross_amount' => $gross,
                    'net_amount' => $gross
                ]);
            }
            else{

                $delete = SalesDetail::whereId($item->id)->forceDelete();

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
        return back()->with('success','Successfully updated sales record');
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
            if($item->product_id == 42){
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
                $del_fee = Deliverablecities::where('name',$sales->customer_location)->where('item_type',$rate_type)->first();
               
                $delivery_amount = $del_fee?->rate ?? 0;

                if($baka == 1 && $del_fee->outside_manila == 1){
                    $delivery_amount = 3000;
                }
            }
            else{
                $delivery_amount = $sales->delivery_fee_amount;
            }
        }
        $update_header = SalesHeader::whereId($sales->id)->update([
            'delivery_fee_amount' => $delivery_amount,
            'gross_amount' => ($gross + $delivery_amount), 
            'net_amount' => ($gross + $delivery_amount) - $sales->discount_amount,
            'payment_status' => 'UNPAID'
        ]);
        
    }

    public function prepare_dateneeded(Request $request){
        //({{$sale->id}},'{{ $dateneeded }}','{{$sale->delivery_type}}','{{$locationed}}','{{$sale->instruction}}','{{$sale->customer_delivery_adress}}');

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
        // dd($request->all());
        $sales = SalesHeader::whereId($request->update_dateneeded_id)->first();
    
        if(isset($request->delivery_branch)){
            SalesHeader::whereId($request->update_dateneeded_id)->update(['delivery_branch' => $request->delivery_branch]);
        }
        
        $update = SalesHeader::whereId($request->update_dateneeded_id)->update([
            'delivery_status' => ''
        ]);

        $update_date_needed = SalesDetail::where('sales_header_id',$request->update_dateneeded_id)->update([
            'delivery_date' => $request->update_dateneeded_date." ".$request->update_dateneeded_time
        ]);

        // Insert new addresses
        if ($request->filled('address')) {
            ProductDeliveryAddress::where('sales_header_id', $request->update_dateneeded_id)->delete();

            foreach ($request->address as $index => $addr) {

                $prods = [];

                $products = Product::whereIn('id', $request->product_ids[$index])->get();

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
                        'location' => $request->location[$index] ?? null,
                        'note' => $request->note[$index] ?? null,
                        'contact_person' => $request->contact_person[$index] ?? null,
                        'contact_tel' => $request->contact_tel[$index] ?? null,
                        'products' => json_encode($prods),
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
                if($d->product_id == 42){
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
                $del_fee = Deliverablecities::where('name',$request->update_dateneeded_d2d)->where('item_type',$rate_type)->first();           
                $delivery_amount = $del_fee->rate;
                if($baka == 1 && $del_fee->outside_manila == 1){
                    $delivery_amount = 3000;
                }
            }

            $amt = ($sales->gross_amount - $sales->delivery_fee_amount) + $delivery_amount;

            if($sales->customer_location == $request->update_dateneeded_d2d){

                $update_date_needed = SalesHeader::whereId($request->update_dateneeded_id)->update([               
                    'customer_delivery_adress' => $request->new_delivery_address,
                    'instruction' => $request->new_instruction,
                    'delivery_fee_amount' => $delivery_amount,
                    'gross_amount' => $amt,
                    'net_amount' => $amt,
                    'customer_address' => $request->new_delivery_address
                ]);

            }else{

                $update_date_needed = SalesHeader::whereId($request->update_dateneeded_id)->update([
                    'customer_location' => $request->update_dateneeded_d2d,
                    'customer_delivery_adress' => $request->new_delivery_address,
                    'instruction' => $request->new_instruction,
                    'delivery_fee_amount' => $delivery_amount,
                    'gross_amount' => $amt,
                    'net_amount' => $amt,
                    'customer_address' => $request->new_delivery_address
                ]);

            }
        }
        if($request->shipping_type == 'storepickup'){

            $gross = $sales->gross_amount - $sales->delivery_fee_amount;
            $update_date_needed = SalesHeader::whereId($request->update_dateneeded_id)->update([
                'customer_delivery_adress' => $request->update_dateneeded_sp,
                'instruction' => $request->new_instruction,
                'outlet' => $request->update_dateneeded_sp,
                'gross_amount' => $gross,
                'net_amount' => $gross,
                'delivery_fee_amount' => 0,
                'delivery_type' => 'Store Pickup',
                'customer_address' => $request->update_dateneeded_sp,
                'customer_delivery_adress' => $request->update_dateneeded_sp,
                'customer_location' => ''
            ]);
        }

        return back()->with('success','Sales details has been updated!');
    }

    public function approve_payment(Request $request){

        $orig_payment = SalesPayment::whereId($request->confirm_payment_id)->first();
        $image_url = $orig_payment->file_url;
        if($request->hasFile('confirm_payment_file'))
        {
            $newFile = $this->upload_file_to_storage('payments', $request->file('confirm_payment_file'));
            $image_url = $newFile['url'];
        }

        $s = SalesPayment::whereId($request->confirm_payment_id)->update(
            [
                'status' => 'PAID',
                'file_url' => $image_url,
                'receipt_number' => $request->confirm_payment_ref ?? $orig_payment->receipt_number
            ]
        );
        $data = SalesPayment::whereId($request->confirm_payment_id)->first();
        if($data->payment_type == 'Gift Cert'){

            $update_gift_cert = GiftCertificate::where('code',$data->receipt_number)->where('isApproved','<>','1')
                                ->update([
                                    'isApproved' => '1',
                                    'approved_by' => Auth::user()->name,
                                    'approved_on' => date('Y-m-d')
                                ]);
            if($update_gift_cert){
                $discounts = SalesPayment::where('sales_header_id',$data->sales_header_id)->whereStatus('PAID')->sum('amount');
                $grand_gross = $data->gross_amount - $discounts;
                $update_sales_header = SalesHeader::whereId($data->sales_header_id)->update([
                    'net_amount' => $grand_gross,
                    'discount_amount' => $coupon_amount
                ]);
            }
        }


        $ran = microtime();
        $today = getdate();
        $approvalId = 'approval_'.$today[0].substr($ran, 2,6);
        $approval_store = Approvals::create([
            'approval_code' => $approvalId,
            'user_id' => Auth::id(),
            'approval_type' => 'Payment',
            'reference_id' => $data->sales_header_id,
            'remarks' => $request->confirm_payment_remarks
        ]);


        if(!empty($data->sales->email)){
           Mail::to($data->sales->email)->send(new PaymentApprovedAdmin($data));
        }


        $sales = SalesHeader::whereId($data->sales_header_id)->first();

        $sms = new Sms();
        $sms->send_sms($sales->customer_contact_number, 'payment_update', $data);

        /*
        $balance = SalesHeader::balance($sales->id);
        if( $balance <= 0 ){
            $this->confirm_order($sales->id, 'Auto confirm after payment completion', Auth::user()->name);
        }
        */
        $total_paid = SalesHeader::paid($sales->id);
        if( $total_paid > 0 && $sales->isConfirm == 0){
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

        $update_gross_and_net = SalesHeader::whereId($request->delfee_sales_id)->update([
            'gross_amount' => $new_gross,
            'net_amount' => $new_net
        ]);
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
        $sales->update([
            'isConfirm' => 1,
            'confirmed_by' => $confirm_by,
            'confirm_remarks' => $remarks,
            'confirmed_on' => date('Y-m-d H:i:s')
        ]);

        $sh = new SalesHeader();
        $sh->assign_to_production_branch($sales, 1);
    
        //$mobile = SalesHeader::whereId($order_id)->first();
        
        /*
        $sms = new Sms();
        $sms->send_sms($mobile->customer_contact_number, 'confirm_order', $mobile);
        */
        
        return true;
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
        if(auth()->user()->role_id == 1 || auth()->user()->role_id == 3 || auth()->user()->role_id == 5 || auth()->user()->role_id == 13 ){
            // $model = SalesHeader::where('id','>',0);

            if (auth()->user()->role_id == 5) {
                $branchId = auth()->user()->role_id == 5 ? auth()->user()->production_branch_id : null;

                $eligible = DB::table('ecommerce_sales_details as d')
                    ->join('job_orders as jo', 'jo.sales_detail_id', '=', 'd.id')
                    ->join('production_orders as po', 'po.joborder_id', '=', 'jo.id')
                    ->when($branchId, function ($query) use ($branchId) {
                        return $query->where('po.branch_id', $branchId);
                    })
                    ->select('d.sales_header_id');

                // $model = DB::table('ecommerce_sales_headers')
                //     ->joinSub($eligible, 'eh', function ($j) {
                //         $j->on('eh.sales_header_id', '=', 'ecommerce_sales_headers.id');
                //     });

                $model = SalesHeader::where(function ($query) use($eligible) {
                    $query->whereIn('id', $eligible)->where('has_sub', 0);
                });
            } else {
                $model = SalesHeader::where('id','>',0)->where('has_sub', 0);
            }

        }else{
            $branches = UserBranch::accessBranch();

            $locations = [];
            foreach($branches as $branch){
                
                array_push($locations, $branch->branch->name);
            }


            $model = SalesHeader::where('id','>',0)
                                ->where(function ($query) use($locations) {
                                    $query->whereIn('outlet', $locations)
                                          ->orWhereIn('order_source', $locations);
                                });

        }
        $model = $this->additional_filters($model);
      

        $selectFields = ['id','order_source','delivery_type','contact_person','instruction','customer_delivery_adress','outlet','customer_location','order_number', 'customer_name', 'customer_location', 'isConfirm', 'created_at', 'status', 'delivery_status', 'payment_status', 'net_amount', 'gross_amount','deleted_at', DB::raw('(SELECT ecommerce_sales_details.delivery_date From ecommerce_sales_details WHERE ecommerce_sales_headers.id=ecommerce_sales_details.sales_header_id GROUP BY ecommerce_sales_details.sales_header_id) as date_needed')];

        $filterFields = ['order_number', 'customer_name', 'date_needed'];
        $listing = new ListingHelper('desc',20,'order_number', $customConditions);
        $sales = $listing->filter_fields($filterFields)->simple_search_using_collection($model, $this->searchFields,  [],  [], [], $selectFields, $filterFields);

        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search_using_collection';
        //dd($sales);


        return view('admin.sales.index',compact('sales','filter','searchType'));

    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
    
        SalesHeader::whereIn('id', $ids)
            ->where('isConfirm', '!=', 1)
            ->delete();
    
        return redirect()->back()->with('success', 'Selected sales have been deleted.');
    }

    public function bulkDeleteMixed(Request $request)
    {
        $records = json_decode($request->input('records'), true);

        foreach ($records as $record) {
            if ($record['type'] === 'sales') {
                SalesHeader::find($record['id'])?->delete();
            } elseif ($record['type'] === 'job') {
                JobOrder::find($record['id'])?->delete();
            }
        }

        return redirect()->back()->with('success', 'Selected records deleted successfully.');
    }

    public function additional_filters($model){
    
        if(isset($_GET['order_source']) && strlen($_GET['order_source']) > 1){
            $model = $model->where('order_source','=',$_GET['order_source']);        
        }
        if(isset($_GET['order_status']) && strlen($_GET['order_status']) > 0){
            if($_GET['order_status'] == 2){
                $model = $model->where('delivery_status','=','Open Date');        
            }
            elseif ($_GET['order_status'] == 'Cancelled') {
                $model = $model->where('status','=','Cancelled');        
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

        ActivityLog::create([
            'created_by' => auth()->id(),
            'activity_type' => 'delete',
            'dashboard_activity' => 'delete Sales Transaction',
            'activity_desc' => 'deleted Sales Transaction with Order Number: '.$sale->order_number,
            'activity_date' => date("Y-m-d H:i:s"),
            'db_table' => 'ecommerce_sales_headers',
            'old_value' => '',
            'new_value' => '',
            'reference' => $sale->id
        ]);

        $sale->delete();

        return back()->with('success','Successfully deleted transaction');
    }

    public function restore($sales)
    {
        SalesHeader::withTrashed()->findOrFail($sales)->restore();

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
        $totalPayment = SalesPayment::where('sales_header_id',$request->id)->sum('amount');
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

        if ($sales->is_sub == 1) {
            $subSales = SalesHeader::where('id', $sales->parent_sales_header_id)->first();
            $salesPayments = $subSales->payments ?? collect();
            $totalPayment = SalesPayment::where('sales_header_id', $sales->parent_sales_header_id)->sum('amount');
        } else {
            $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
            $totalPayment = SalesPayment::where('sales_header_id',$id)->sum('amount');
        }

        if (!$sales) {
            return redirect()->route('sales-transaction.index')->with('error', 'Sales record not found.');
        }
        

        $gc = GiftCertificate::where('sales_header_id',$id)->get();
        $salesDetails = SalesDetail::where('sales_header_id',$id)->get();
        $deliveries = DeliveryStatus::where('order_id',$id)->get();
        $deliveries = DeliveryStatus::where('order_id',$id)->get();
        $totalNet = SalesHeader::where('id',$id)->sum('net_amount');

        if($totalNet <= $totalPayment){
            $status = 'PAID';
        }
        else {
            $status = 'UNPAID';
            if($totalPayment > 0){
                $status = 'PARTIAL';
            }
        }


        return view('admin.sales.view',compact('sales','salesPayments','salesDetails','status','deliveries','gc'));
    }
    
    public function update_sales_details($id)
    {
        $salesdetail = SalesDetail::where('sales_header_id',$id)->first();
        $salesheader = SalesHeader::with('deliveryAddress')->find($id);

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

        if($salesheader->delivery_type == 'Door to door delivery'){
            $locationed = $salesheader->customer_location;
        }
        if($salesheader->delivery_type == 'Store Pickup'){
            $locationed = $salesheader->outlet;
        }

        $branches_store = Branch::orderBy('name','asc')->get();

        // dd($salesheader->deliveryAddress);

        $locations = Deliverablecities::distinct()->orderBy('name')->get(['name']);

        $locations = $locations->filter(function ($value) {
            return !is_null($value->name) && $value->name !== '';
        })->values();
      
        ActivityLog::create([
            'created_by' => auth()->id(),
            'activity_type' => 'update',
            'dashboard_activity' => 'update Sales Details',
            'activity_desc' => 'updated Sales Details with Order Number: '.$salesheader?->order_number,
            'activity_date' => date("Y-m-d H:i:s"),
            'db_table' => 'ecommerce_sales_details',
            'old_value' => '',
            'new_value' => '',
            'reference' => $salesdetail?->id
        ]);

        return view('admin.sales.update_sales_detail',compact('salesheader','dateneeded','date_only','time_only','locationed','products','branches_store', 'locations'));

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

            if ($request->has('image')) {
                if($request->hasFile('image')){
                    $image = $request->file('image');
                    $imageName = time().'.'.$image->getClientOriginalExtension();

                    if (!file_exists(public_path('/images/proof-of-delivery'))) {
                        mkdir(public_path('/images/proof-of-delivery'), 0777, true);
                    }
                    $destinationPath = public_path('/images/proof-of-delivery');
                    $image->move($destinationPath, $imageName);

                    $data['image'] = $imageName;
                }
            }

            $update_delivery_table = DeliveryStatus::create($data);

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

            if ($request->has('image')) {
                if($request->hasFile('image')){
                    $image = $request->file('image');
                    $imageName = time().'.'.$image->getClientOriginalExtension();

                    if (!file_exists(public_path('/images/proof-of-delivery'))) {
                        mkdir(public_path('/images/proof-of-delivery'), 0777, true);
                    }
                    $destinationPath = public_path('/images/proof-of-delivery');
                    $image->move($destinationPath, $imageName);

                    $data['image'] = $imageName;
                }
            }

            $update_delivery_table = DeliveryStatus::create($data);

            if(!empty($update_delivery_table->sales->email)){
                Mail::to($update_delivery_table->sales->email)->send(new DeliveryMovement($update_delivery_table));
            }

            $sms = new Sms();
            $order =  SalesHeader::where('id',$request->del_id)->with('deliveryAddress', 'items', 'couponUsed', 'user')->first();
            $order->is_new_order = 0;
            $order->save();
            if($order->customer_contact_number && ($request->delivery_status == 'Ready For delivery' || $request->delivery_status == 'Delivered/Picked Up' || $request->delivery_status == 'In Transit')){
                
                $sms->send_sms($order->customer_contact_number, 'delivery_update', $order);

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

            $this->sms_send_order_status($order->customer_contact_number, $order);
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

        return back()->with('success','Successfully updated delivery status!');

    }

    public function showDeliveryStatus($id)
    {
        $status = DeliveryStatus::where('order_id', $id)->orWhere('job_order_id', $id)->latest()->first();
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
			$headers[] = 'Authorization: Bearer dwD2PXjYKV9kQv6KAI1l4ohYEjuOEwIoeoTPtwrEkU';
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

        if (isset($sale->is_sub) && $sale->is_sub == 1) {
            $parentSale = SalesHeader::where('id', $sale->parent_sales_header_id)->first();
            $payments = SalesPayment::where('sales_header_id', $parentSale->id)->get();
        } else {
            $payments = SalesPayment::where('sales_header_id',$request->id)->get();
        }

        return view('admin.sales.added-payments-result',compact('payments'));
    }


    public function display_delivery(Request $request){

        $input = $request->all();

        $type = $request->has('type') ? $request->type : 'sales';

        if ($type === 'job') {
            $delivery = DeliveryStatus::where('job_order_id',$request->id)->where('type', 'joborder')->get();
        } else {
            $delivery = DeliveryStatus::where('order_id',$request->id)->where('type', 'sales')->get();
        }

        return view('admin.sales.delivery_history',compact('delivery'));
    }
    
    public function sales_printout($id)
    {
        $id = base64_decode($id);
        
        $sales = \App\EcommerceModel\SalesHeader::with('couponUsed')->where('id',$id)->first();

        if ($sales->is_sub == 1) {
            $subSales = SalesHeader::where('id', $sales->parent_sales_header_id)->first();
            $salesPayments = $subSales->payments ?? collect();
        } else {
            $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        }

        if (!$sales) {
            return redirect()->route('sales-transaction.index')->with('error', 'Sales record not found.');
        }

        $salesDetails  = SalesDetail::where('sales_header_id',$id)->get();
        $deliveries    = DeliveryStatus::where('order_id',$id)->get();

        $gc = GiftCertificate::where('sales_header_id',$id)->get();

        return view('admin.sales.print',compact('sales','salesPayments','salesDetails','deliveries','gc'));
    }
    
    public function update_delivery_branch(Request $request)
    {
        //
    }

    public function payments()
    {
        $payments = SalesPayment::where('sales_header_id','>',0)->orderBy('id','desc');
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

}
