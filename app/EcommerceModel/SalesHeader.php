<?php

namespace App\EcommerceModel;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class SalesHeader extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_sales_headers';
    protected $fillable = ['user_id', 'order_number', 'response_code', 'customer_name', 'customer_contact_number', 'customer_address', 'customer_delivery_adress', 'delivery_tracking_number', 'delivery_fee_amount',
        'gross_amount', 'tax_amount', 'net_amount', 'discount_amount', 'payment_status',
        'delivery_status', 'status', 'currency','order_source','payment_type','delivery_type','order_type','outlet','receipt_number','instruction','agent','customer_location','email','payment_used','payment_remarks','contact_person','isConfirm','confirmed_by','confirmed_on','confirm_remarks','origin','delivery_branch','forecast_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        $cntr=0;
        foreach($this->payments as $p){
            if(strtoupper($p->status)=='PENDING')
                $cntr++;
        }
        return $cntr;
    }

    public function getPaymentadminstatusAttribute()
    {
       $amount = $this->gross_amount;
       $paid = (float) SalesPayment::where('sales_header_id',$this->id)->whereStatus('PAID')->sum('amount');
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
        $amount = SalesHeader::whereId($id)->sum('gross_amount');
        $paid = (float) SalesPayment::where('sales_header_id',$id)->whereStatus('PAID')->sum('amount');
        return ($amount - $paid);
    }

    public static function paid($id){
        $paid = SalesPayment::where('sales_header_id',$id)->whereStatus('PAID')->sum('amount');
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

    public static function payment_status($order_num){
        $data = SalesHeader::where('order_number',$order_num)->first();

        if($data->payment_status == 'Completed'){
            return 'PAID';
        } else {
            return 'UNPAID';
        }
    }

    public static function status(){
        $data = SalesHeader::where('status','PAID')->first();
        if(!empty($data)){
            return $data;
        } else {
            return NULL;
        }

    }

    public function getPaymentstatusAttribute(){
        $paid = SalesPayment::where('sales_header_id',$this->id)->whereStatus('PAID')->sum('amount');

        if($paid >= $this->net_amount){
            $tag_as_paid = SalesHeader::whereId($this->id)->update(['payment_status' => 'PAID']);
            if($this->delivery_status == 'Waiting for Payment' || $this->delivery_status == '' ){
                $update_delivery_status = SalesHeader::whereId($this->id)->update(['delivery_status' => 'Processing Stock']);
            }
            return 'PAID';
        }else{
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

    public static function boot()
    {
        parent::boot();

        self::updating(function($model) {
            self::$oldModel = $model->fresh();
        });

        self::updated(function($model) {
            $name = $model[self::$name];
            $unrelatedFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
            $oldModel = self::$oldModel->toArray();
            foreach ($oldModel as $fieldName => $value) {
                if (in_array($fieldName, $unrelatedFields)) {
                    continue;
                }

                $oldValue = $model[$fieldName];
                if ($oldValue != $value) {
                    $fieldNames = implode(' ', explode('_', $fieldName));
                    ActivityLog::create([
                        'created_by' => auth()->id(),
                        'activity_type' => 'update',
                        'dashboard_activity' => 'updated the '. self::$tableTitle .' '. $fieldNames,
                        'activity_desc' => 'updated the '. self::$tableTitle .' '. $fieldNames .'of '. $name .' from '. $oldValue .' to '. $value,
                        'activity_date' => date("Y-m-d H:i:s"),
                        'db_table' => $model->getTable(),
                        'old_value' => $oldValue,
                        'new_value' => $value,
                        'reference' => $model->id
                    ]);
                }
            }
        });

        self::deleted(function($model){
            $name = $model[self::$name];
            ActivityLog::create([
                'created_by' => auth()->id(),
                'activity_type' => 'delete',
                'dashboard_activity' => 'deleted a '. self::$tableTitle,
                'activity_desc' => 'deleted the '. self::$tableTitle .' '. $name,
                'activity_date' => date("Y-m-d H:i:s"),
                'db_table' => $model->getTable(),
                'old_value' => $name,
                'new_value' => '',
                'reference' => $model->id
            ]);
        });
    }
}
