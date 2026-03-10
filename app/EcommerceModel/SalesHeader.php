<?php

namespace App\EcommerceModel;

use App\Models\ActivityLog;
use App\Models\ProductDeliveryAddress;
use App\Models\User;
use App\EcommerceModel\ProductionBranch;
use App\EcommerceModel\JobOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\Concerns\LogsActivityDiff;

class SalesHeader extends Model
{
    use SoftDeletes, LogsActivityDiff;
    
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $table = 'ecommerce_sales_headers';
    protected $fillable = ['updated_at', 'created_at', 'user_id', 'order_number', 'response_code', 'customer_name', 'customer_contact_number', 'customer_address', 'customer_delivery_adress', 'delivery_tracking_number', 'delivery_fee_amount',
        'gross_amount', 'parent_sales_header_id', 'has_sub', 'is_sub', 'has_dispatched', 'has_transited', 'is_new_order', 'tax_amount', 'net_amount', 'discount_amount', 'payment_status', 'province', 'city', 'barangay',
        'delivery_status', 'temp_prod_branch', 'status', 'for_deletion', 'currency','order_source','payment_type','delivery_type','order_type','outlet','receipt_number','instruction','agent','customer_location','email','payment_used','payment_remarks','contact_person','isConfirm','confirmed_by','confirmed_on','confirm_remarks','origin','delivery_branch','forecast_date', 'is_multiple_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assign_to_production_branch($sale, $pb){
        //dd($sale);
        $items = $sale->items;
        foreach($items as $salesdetail){

            $current_total_order = JobOrder::whereDate('date_needed',date('Y-m-d',strtotime($salesdetail->delivery_date)))->count();
            $insertID = $current_total_order+1;
            

            $jo = JobOrder::updateOrCreate(
                [
                    'sales_detail_id' => $salesdetail->id,
                    'product_id' => $salesdetail->product_id
                ],
                [
                'user_id' => auth()->check() ? auth()->id() : 1,
                'jo_number' => 'JO'.date('Ymd',strtotime($salesdetail->delivery_date)).sprintf('%04d', $insertID),
                'sales_number' => $sale->order_number,
          
                'order_source' => $sale->order_source,
         
                'product_name' => $salesdetail->product->name,
                'product_size' => $salesdetail->product->size,
                'product_weight' => $salesdetail->product->weight,
                'product_category' => $salesdetail->product->category_id ?? null,
                'price' => $salesdetail->price,
                'paella_qty' => $salesdetail->paella_qty,
                'qty' => $salesdetail->qty,
                'paella_price' => $salesdetail->paella_price,
                'customer_name' => $sale->customer_name,
                'date_needed' => $salesdetail->delivery_date,
                'customer_mobile_number' => $sale->customer_contact_number,
                'customer_tel_number' => $sale->customer_contact_number,
                'customer_address' => $sale->customer_address,
                'customer_delivery_adress' => $sale->customer_delivery_adress,
                'delivery_tracking_number' => '',
                'delivery_method' => $sale->delivery_type,
                'pickup_branch' => $sale->outlet,
                'delivery_status' => 'On Processed',
                'status' => 'Active',
                'jo_category' => 'Order',
                'jo_order_type' => $sale->order_type ?? ' '

            ]);

            //if($jo){
                ProductionOrder::updateOrCreate(
                [
                    'joborder_id' => $jo->id
                ],
                [
                    'branch_id' => $pb ?? 1,                    
                    'delivery_date' => $salesdetail->delivery_date,
                    'schedule_type' => $sale->order_type ?? ' '
                ]);
            //}

        }
    }

    public function payments()
    {
        return $this->hasMany('App\EcommerceModel\SalesPayment','sales_header_id');
    }
    
    public function getHashOrderNumberAttribute(){
        return base64_encode($this->id);
    }
    
    // public function getDeliveryStatusAttribute($value){
     
    //     $stat = $value;

    //     if($value == ''){
    //         if($this->Paymentadminstatus <> 'UNPAID'){
    //             $this->update(['delivery_status' => 'Processing']);
    //             $stat = 'Processing';
    //         }
    //     }

    //     return $stat;
    // }

    public function getPaymentspendingtotalAttribute()
    {
        $sale = SalesHeader::withTrashed()->whereId($this->id)->first();

        if (isset($sale->is_sub) && $sale->is_sub == 1) {
            $parentSale = SalesHeader::withTrashed()->where('id', $sale->parent_sales_header_id)->first();
            $payments = SalesPayment::where('sales_header_id', $parentSale->id)->get();
        } else {
            $payments = SalesPayment::where('sales_header_id', $sale->id)->get();
        }

        $cntr=0;
        foreach($payments as $p){
            if(strtoupper($p->status)=='PENDING')
                $cntr++;
        }
        return $cntr;
    }

    public function deliveryStatus()
    {
        return $this->hasMany(DeliveryStatus::class, 'order_id');
    }

    public function getPaymentadminstatusAttribute()
    {
        $amount = floatval($this->net_amount);
        $sale = SalesHeader::withTrashed()->find($this->id);

        if (isset($sale->parent_sales_header_id) && $sale->parent_sales_header_id != null) {
            $payment = SalesPayment::where('sales_header_id', $sale->parent_sales_header_id)->where('status', 'PAID')->get();
            $paid = (float) $payment->sum('amount');
        } else {
            $payment = SalesPayment::where('sales_header_id', $sale->id)->where('status', 'PAID')->get();
            $paid = (float) $payment->sum('amount');
        }

        if (isset($sale->parent_sales_header_id) && $sale->parent_sales_header_id != null) {
            $newSale = SalesHeader::where('id', $sale->parent_sales_header_id)->first();
        } else {
            $newSale = SalesHeader::where('id', $sale->id)->first();
        }

        // if ($newSale->payment_status == 'PAID') {
        //     return 'PAID';
        // }
        $balance = $amount - $paid;
        if($balance <= 0){
            return 'PAID';
        }
        else{
            if($paid > 0){
                return 'PARTIAL';
            }
            else{
                return 'UNPAID';
            }
        }
    }

    public static function balance($id){
        $sales = SalesHeader::withTrashed()->whereId($id)->first();

        if ($sales->is_sub == 1) {
            $sale = SalesHeader::withTrashed()->where('id', $sales->parent_sales_header_id)->first();
            $amount = $sale->net_amount;
        } else {
            $sale = SalesHeader::withTrashed()->whereId($id)->first();
            $amount = $sale->net_amount;
        }

        if ($sales->is_sub == 1) {
            $payments = SalesPayment::where('sales_header_id', $sales->parent_sales_header_id)->where('status', 'PAID')->get();
            $paid = (float) $payments->sum('amount');
        } else {
            $payments = SalesPayment::where('sales_header_id',$id)->where('status', 'PAID')->get();
            $paid = (float) $payments->sum('amount');
        }

        $total = $amount - $paid;

        if($total <= 0){
            $total = 0;
        }
        return $total;
    }

    public static function paid($id){
        $sales = SalesHeader::withTrashed()->whereId($id)->first();

        if ($sales->is_sub == 1) {
            $paid = SalesPayment::where('sales_header_id',$sales->parent_sales_header_id)->where('status', 'PAID')->sum('amount');
        } else {
            $paid = SalesPayment::where('sales_header_id',$id)->where('status', 'PAID')->sum('amount');
        }

        logger('$paid: '.$paid);

        // $paid = SalesPayment::where('sales_header_id',$id)->whereStatus('PAID')->sum('amount');
        return $paid;
    }

    public function items(){
    	return $this->hasMany('App\EcommerceModel\SalesDetail','sales_header_id');
    }

    public function deliveries(){
        return $this->hasMany('App\EcommerceModel\DeliveryStatus','order_id');
    }

    public function customer_details(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // public static function payment_status($order_num){
    //     $data = SalesHeader::where('order_number',$order_num)->first();

    //     if($data->payment_status == 'Completed'){
    //         return 'PAID';
    //     } else {
    //         return 'UNPAID';
    //     }
    // }

    public static function status(){
        $data = SalesHeader::withTrashed()->where('status','PAID')->first();
        if(!empty($data)){
            return $data;
        } else {
            return NULL;
        }

    }

    public function getPaymentStatusAttribute($value)
    {
        // $sale = SalesHeader::find($this->id);

        // if (isset($sale->parent_sales_header_id) && $sale->parent_sales_header_id != null) {
        //     $paid = SalesPayment::where('sales_header_id', $sale->parent_sales_header_id)
        //         ->where('status', 'PAID') 
        //         ->sum('amount') ?? 0;
        // } else {
        //     $paid = SalesPayment::where('sales_header_id', $this->id)
        //         ->where('status', 'PAID') 
        //         ->sum('amount') ?? 0;
        // }

        // // Use the raw/current saved status to avoid recursion
        // $current = $value ?? $this->getRawOriginal('payment_status');

        // if ($paid >= $this->gross_amount && $current !== 'PAID') {
        //     static::whereKey($this->id)->update([
        //         'payment_status' => 'PAID',
        //         'updated_at'     => $this->created_at,
        //     ]);

        //     if (
        //         ($this->delivery_status === 'Waiting for Payment' || $this->delivery_status === '') &&
        //         $this->delivery_status !== 'Processing Stock'
        //     ) {
        //         static::whereKey($this->id)->update([
        //             'delivery_status' => 'Processing Stock',
        //             'updated_at'      => $this->created_at,
        //         ]);
        //     }

        //     return 'PAID';
        // }

        // // Normalize legacy 'Completed' to PAID
        // return in_array($current, ['PAID', 'Completed'], true) ? 'PAID' : 'UNPAID';

        $sales = SalesHeader::withTrashed()->whereId($this->id)->first();

        if ($sales->is_sub == 1) {
            $paid = SalesPayment::where('sales_header_id',$sales->parent_sales_header_id)->where('status', 'PAID')->sum('amount');
        } else {
            $paid = SalesPayment::where('sales_header_id',$this->id)->where('status', 'PAID')->sum('amount');
        }

        if ($paid >= $this->net_amount) {
            SalesHeader::whereId($this->id)->update(['payment_status' => 'PAID']);
            if ($this->delivery_status == 'Waiting for Payment' || $this->delivery_status == '') {
                SalesHeader::whereId($this->id)->update(['delivery_status' => 'Processing Stock']);
            }
            return 'PAID';
        } else {
            return 'UNPAID';
        }
    }
    
    public static function media_color($media) {

        switch($media){
            case 'Facebook':
                return '#3b5998';
            break;

            case 'Twitter':
                return '#00aced';
            break;

            case 'Youtube':
                return '#bb0000';
            break;

            case 'Instagram':
                return '#517fa4';
            break;

            default:
                return '#004E1F';
        }
    }

    public static function random_color()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
    
    public static function monthly_sales($yr)
    {
        $total_sales = '';
        $month_num  = date('m');
        for ($x = 1; $x <= $month_num; $x++) {

            $sales = DB::select("select sum(net_amount) as total_sale from ecommerce_sales_headers where year(created_at) = '$yr' and month(created_at) = $x and status = 'active' and payment_status = 'PAID' ");

            if(isset($sales[0]->total_sale)){
                $total = number_format($sales[0]->total_sale,2,'.','');
            } else {
                $total = 0;
            }

            $total_sales .= $total.',';
        }

        return $total_sales;
    }

    public static function socmed_order_volume($media,$startdate,$enddate)
    {
        $qry ="select sum(d.qty) as volume from ecommerce_sales_details d left join ecommerce_sales_headers h on h.id = d.sales_header_id where h.status = 'active' and h.payment_status = 'PAID' and h.created_at >='".date('Y-m-d',strtotime($startdate))." 00:00:00.000' and h.created_at <='".date('Y-m-d',strtotime($enddate))." 23:59:59.999' ";

        if($media == ''){
            $qry .= " and h.origin IS NULL";
        } else {
            $qry .= " and h.origin = '$media'";
        }

        $order = DB::select($qry);
        
        return number_format($order[0]->volume,0);  
    }

    public static function branch_order_volume($branch,$startdate,$enddate)
    {
        $order = DB::select("select sum(d.qty) as volume from ecommerce_sales_details d left join ecommerce_sales_headers h on h.id = d.sales_header_id where h.order_source = '$branch' and h.status = 'active' and h.payment_status = 'PAID' and h.created_at >='".date('Y-m-d',strtotime($startdate))." 00:00:00.000' and h.created_at <='".date('Y-m-d',strtotime($enddate))." 23:59:59.999' ");

        return number_format($order[0]->volume,0);  
    }
    
    
    
    
    
    
    


    // Need to change every model
    static $oldModel;
    static $tableTitle = 'sales transaction';
    static $name = 'order_number';
    // END Need to change every model

    // public static function boot()
    // {
    //     parent::boot();

    //     self::updating(function($model) {
    //         self::$oldModel = $model->fresh();
    //     });

    //     self::updated(function($model) {
    //         $name = $model[self::$name];
    //         $unrelatedFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    //         $oldModel = self::$oldModel->toArray();
    //         foreach ($oldModel as $fieldName => $value) {
    //             if (in_array($fieldName, $unrelatedFields)) {
    //                 continue;
    //             }

    //             $oldValue = $model[$fieldName];
    //             if ($oldValue != $value) {
    //                 $fieldNames = implode(' ', explode('_', $fieldName));
    //                 ActivityLog::create([
    //                     'created_by' => auth()->id(),
    //                     'activity_type' => 'update',
    //                     'dashboard_activity' => 'updated the '. self::$tableTitle .' '. $fieldNames,
    //                     'activity_desc' => 'updated the '. self::$tableTitle .' '. $fieldNames .'of '. $name .' from '. $oldValue .' to '. $value,
    //                     'activity_date' => date("Y-m-d H:i:s"),
    //                     'db_table' => $model->getTable(),
    //                     'old_value' => $oldValue,
    //                     'new_value' => $value,
    //                     'reference' => $model->id
    //                 ]);
    //             }
    //         }
    //     });

    //     self::deleted(function($model){
    //         $name = $model[self::$name];
    //         ActivityLog::create([
    //             'created_by' => auth()->id(),
    //             'activity_type' => 'delete',
    //             'dashboard_activity' => 'deleted a '. self::$tableTitle,
    //             'activity_desc' => 'deleted the '. self::$tableTitle .' '. $name,
    //             'activity_date' => date("Y-m-d H:i:s"),
    //             'db_table' => $model->getTable(),
    //             'old_value' => $name,
    //             'new_value' => '',
    //             'reference' => $model->id
    //         ]);
    //     });
    // }

    public function deliveryAddress()
    {
        return $this->hasMany(ProductDeliveryAddress::class, 'sales_header_id')->orderBy('id', 'asc');
    }

    public function deliveryStatuses()
    {
        return $this->hasMany(DeliveryStatus::class, 'order_id', 'id');
    }

    public function couponUsed()
    {
        return $this->hasMany(CouponCart::class, 'sales_header_id');
    }

    public function subHeaders()
    {
        return $this->hasMany(self::class, 'parent_sales_header_id', 'id');
    }

    public function hasPartialPayment()
    {
        $payments = $this->payments()->where('status', 'PAID')->get();
        $paidAmount = $payments->sum('amount');

        return $paidAmount > 0 || $this->isConfirm == 1; 
    }
}
