<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesPayment extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_sales_payments';
    protected $fillable = ['sales_header_id','payment_type','amount','status', 'payment_date', 'receipt_number','created_by'
    ,'order_number','remark','trans_id','err_desc','signature','cc_name','cc_no','bank_name','country','file_url'
];

    
    public static function check_if_has_added_payments($id)
    {
        $data = SalesPayment::where('sales_header_id',$id)->whereStatus('PAID')->exists();

        if($data){
            return 1;
        } else {
            return 0;
        }
    }

    public static function get_types()
    {
        return [
            "Bank Deposit","Cash","Check Payment","COD","Credit/Debit Card","Discount (Promo)","Discount (VAT)","Discount (Senior Citizen)",
            "Ex-deal","Gcash","Gift Certificate","M Lhuillier","Ok Order","Online Bank Transfer","Open Date Order","Oth","Paymaya","Sign-Chit"
        ];
       
    }

    public function sales()
    {
        return $this->belongsTo('App\EcommerceModel\SalesHeader','sales_header_id')->withTrashed();
    }

    public static function remaining_balance($amount,$id)
    {
        $paid_amount = SalesPayment::where('sales_header_id',$id)->whereStatus('PAID')->sum('amount');

        return $amount-$paid_amount;
    }

    public static function check_if_has_remaining_balance($gross_amount,$id)
    {
        $balance = \App\EcommerceModel\SalesPayment::remaining_balance($gross_amount,$id);
        if($balance == 0){
            return 0;
        } else {
            return 1;
        }

    }

    public static function check_if_has_remaining_unpaid($gross_amount,$id)
    {
        $paid_amount = SalesPayment::where('sales_header_id',$id)->where('Status','<>','CANCELLED')->sum('amount');
        $balance = $gross_amount - $paid_amount;
        if($balance == 0){
            return 0;
        } else {
            return 1;
        }

    }    

    public static function get_remaining_unpaid($gross_amount,$id)
    {
        $paid_amount = SalesPayment::where('sales_header_id',$id)->where('Status','<>','CANCELLED')->sum('amount');
        $balance = $gross_amount - $paid_amount;
        return $balance;

    }
}
