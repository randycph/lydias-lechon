<?php

namespace App\EcommerceModel;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

use App\EcommerceModel\SalesDetail;
class ProductionOrder extends Model
{
    use SoftDeletes;

    protected $table = 'production_orders';
    protected $fillable = ['branch_id', 'joborder_id', 'delivery_date','schedule_type'];

    // public function salesdetail()
    // {
    //     return $this->belongsTo('\App\EcommerceModel\SalesDetail', 'sales_detail_id');
    // }

    public function jobOrder_details()
    {
        return $this->belongsTo('\App\EcommerceModel\JobOrder', 'joborder_id');
    }

    public function prodBranch()
    {
        return $this->belongsTo('\App\EcommerceModel\ProductionBranch', 'branch_id');
    }

    public static function total_order($branch_id,$date)
	{
		$total = ProductionOrder::where('branch_id',$branch_id)->whereDate('delivery_date',$date)->count();

		return $total;
    }
    public static function total_order_forecast($branch_id,$date)
    {
        $orders = ProductionOrder::where('branch_id',$branch_id)->whereDate('delivery_date',$date)->get();
        $total = 0;
        $exclude = ['Delivered'];
        foreach($orders as $order){
            if(!empty($order->jobOrder_details)){
                if($order->jobOrder_details->sales_detail_id > 0){
                    if(!in_array($order->jobOrder_details->sales_detail->header->delivery_status, $exclude)){
                        $total++;
                    }
                }
                else{
                    $total++;
                }
            }else{
                $total++;
            }
         
        }

        return $total;
    }

    // public static function get_remaining_qty($order_id)
    // {
    //     $order_exist = ProductionOrder::where('sales_detail_id',$order_id)->exists();

    //     $data = SalesDetail::where('id',$order_id)->first();

    //     if($order_exist){
    //         $assigned_qty = ProductionOrder::where('sales_detail_id',$order_id)->sum('qty');

    //         return number_format($data->qty-$assigned_qty,2);
    //     } else {
    //         return number_format($data->qty,2);
    //     }
    // }

    // public static function check_if_ther_is_assigned_order($order_id)
    // {
    //     $order_exist = ProductionOrder::where('sales_detail_id',$order_id)->exists();
    //     if($order_exist)
    //         return 1;
    //     else
    //         return 0;
    // }
}
