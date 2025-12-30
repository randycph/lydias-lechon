<?php

namespace App\Http\Controllers\EcommerceControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;

use App\EcommerceModel\ProductionBranch;
use App\EcommerceModel\GiftCertificate;
use App\EcommerceModel\DeliveryStatus;
use App\EcommerceModel\SalesPayment;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesDetail;
use App\EcommerceModel\JobOrder;
use App\EcommerceModel\Branch;
use App\Models\ActivityLog;
use App\Models\ProductCategory;
use App\Models\Permission;
use App\Models\Product;
use App\Models\ProductDeliveryAddress;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function __construct()
    {
        Permission::module_init($this, 'reports');
    }

    public function sales(Request $request)
    {

        $wra="(";
        $wra_array=[];
        $products = Product::where('production_item',1)->where('is_misc',0)->get();
        foreach($products as $p){
            $wra.="'".$p->id."',";
            array_push($wra_array,$p->id);
        }
        $wra = rtrim($wra,",");
        $wra.=")";

        $qry = "SELECT pb.name as prod_branch,jo.jo_number as jnum,h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,d.id as did,p.id as prodid,p.is_misc, h.delivery_branch, time(d.delivery_date) as del_branch
            FROM `ecommerce_sales_details` d
            left join ecommerce_sales_headers h on h.id=d.sales_header_id
            left join products p on p.id=d.product_id
            left join product_categories c on c.id=p.category_id
            left join job_orders jo on jo.sales_detail_id = d.id
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
         where h.id>0 and h.deleted_at is null AND h.for_deletion = 0 and jo.deleted_at is null and d.deleted_at is null AND h.has_sub = 0";
        // conditions
            if(isset($_GET['agent']) && $_GET['agent']<>''){
                $qry.= " and h.agent='".$_GET['agent']."'";
            }
            if(isset($_GET['customer']) && $_GET['customer']<>''){
                $qry.= " and h.customer_name='".$_GET['customer']."'";
            }
            if(isset($_GET['product']) && $_GET['product']<>''){
                $qry.= " and d.product_name='".$_GET['product']."'";
            }
            if(isset($_GET['category']) && $_GET['category']<>''){
                $qry.= " and p.category_id='".$_GET['category']."'";
            }
            if(isset($_GET['order_source']) && $_GET['order_source']<>''){
                $qry.= " and h.order_source='".$_GET['order_source']."'";
            }

            if(isset($_GET['item_type']) && $_GET['item_type']<>''){
                if($_GET['item_type'] == 'WRA'){
                    $qry.= " and p.id in ".$wra;
                }
                elseif($_GET['item_type'] == 'Miscellaneous'){
                    $qry.= " and p.is_misc=1";
                }
                
            }


            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry.= " and h.created_at >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and h.created_at <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
            }
            
            if(isset($_GET['startdateneeded']) && strlen($_GET['startdateneeded'])>=1){
                $qry.= " and d.delivery_date >='".date('Y-m-d',strtotime($_GET['startdateneeded']))." 00:00:00.000' and d.delivery_date <='".date('Y-m-d',strtotime($_GET['enddateneeded']))." 23:59:59.999'";
            }

            if(isset($_GET['ordertype']) && strlen($_GET['ordertype'])>=1){
                $qry.= " and h.order_type='".$_GET['ordertype']."'";
            }

            if(!isset($_GET['startdate'])  && !isset($_GET['startdateneeded'])){
                $qry.= " and h.created_at >='2050-01-01 00:00:00.000'";
            }
            $qry.=" order by time(d.delivery_date)";
            //dd($qry);
            //return $qry;
        // end conditions

        $rs = DB::select($qry);
       
        return view('admin.reports.sales',compact('rs','wra_array'));

    }
    public function sales_transaction(Request $request)
    {
        $qry = "SELECT distinct h.*,h.id as hid,h.created_at as hcreated
            FROM `ecommerce_sales_details` d
            left join ecommerce_sales_headers h on h.id=d.sales_header_id
            left join products p on p.id=d.product_id
            left join product_categories c on c.id=p.category_id
            left join job_orders jo on jo.sales_detail_id = d.id
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
         where h.id>0 and h.deleted_at is null AND h.for_deletion = 0 and jo.deleted_at is null and d.deleted_at is null AND h.has_sub = 0";
        // conditions
            if(isset($_GET['agent']) && $_GET['agent']<>''){
                $qry.= " and h.agent='".$_GET['agent']."'";
            }
            if(isset($_GET['customer']) && $_GET['customer']<>''){
                $qry.= " and h.customer_name='".$_GET['customer']."'";
            }
            if(isset($_GET['product']) && $_GET['product']<>''){
                $qry.= " and d.product_name='".$_GET['product']."'";
            }
            if(isset($_GET['category']) && $_GET['category']<>''){
                $qry.= " and p.category_id='".$_GET['category']."'";
            }
            if(isset($_GET['order_source']) && $_GET['order_source']<>''){
                $qry.= " and h.order_source='".$_GET['order_source']."'";
            }


            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry.= " and h.created_at >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and h.created_at <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
            }
            else{
                $qry.= " and h.created_at >='2050-01-01 00:00:00.000'";
            }
        // end conditions

        $rs = DB::select($qry);
        
        return view('admin.reports.sales-transaction',compact('rs'));

    }

    public function customerSalesReport(Request $request)
    {
        $filters = [];

        $qry = "SELECT 
            h.customer_name,
            u.email,
            u.birthday,
            u.contact_mobile,
            COUNT(d.id) as total_products_purchased,
            SUM(d.price) as total_amount_paid
        FROM ecommerce_sales_headers h
        LEFT JOIN ecommerce_sales_details d ON d.sales_header_id = h.id
        LEFT JOIN products p ON p.id = d.product_id
        LEFT JOIN job_orders jo ON jo.sales_detail_id = d.id
        LEFT JOIN users u ON u.id = h.user_id
        WHERE h.deleted_at IS NULL AND h.for_deletion = 0
        AND jo.deleted_at IS NULL AND d.deleted_at IS NULL AND h.has_sub = 0
        ";


        // Apply filters
        if ($request->has('agent') && $request->agent != '') {
            $qry .= " AND h.agent = '".$request->agent."'";
        }
        if ($request->has('customer') && $request->customer != '') {
            $qry .= " AND h.customer_name = '".$request->customer."'";
        }
        if ($request->has('order_source') && $request->order_source != '') {
            $qry .= " AND h.order_source = '".$request->order_source."'";
        }
        if ($request->has('startdate') && $request->startdate != '') {
            $start = date('Y-m-d', strtotime($request->startdate)) . " 00:00:00.000";
            $end = date('Y-m-d', strtotime($request->enddate)) . " 23:59:59.999";
            $qry .= " AND h.created_at BETWEEN '$start' AND '$end'";
        }

        if (!($request->has('startdate'))) {
            $qry .= " AND h.created_at >= '2050-01-01 00:00:00.000'"; // same logic you use
        }

        $qry .= " GROUP BY h.customer_name
                ORDER BY total_amount_paid DESC"; // optional: order highest paying customer first

        $rs = DB::select($qry);

        return view('admin.reports.customer_details', compact('rs'));
    }


    public function forecaster(Request $request)
    {
       
        $wra="(";
        $wra_array=[];
        $products = Product::where('production_item',1)->where('is_misc',0)->get();
        foreach($products as $p){
            $wra.="'".$p->id."',";
            array_push($wra_array,$p->id);
        }
        $wra = rtrim($wra,",");
        $wra.=")";
        $no_jo = 0;

        // Sales
            $qry = "SELECT d.product_name, d.paella_price, h.contact_person, d.product_name as dproduct_name, h.has_sub, '' as jo_category, d.id as idd,
            d.qty, h.order_number, u.address_street, u.address_municipality, u.address_city, u.address_region,d.price, h.customer_delivery_adress, h.parent_sales_header_id,
            h.customer_name, d.delivery_date as delivery_date, h.instruction, po.delivery_date as deldate, h.delivery_type, jo.jo_number, pb.name as pbname, h.delivery_status as delstat,h.agent, h.customer_contact_number,'' as dr, h.delivery_fee_amount, d.price, '' as releasing, h.order_source, br.name as receiver, c.name as catname, u.name as username, jo.jo_order_type,h.order_type as hordertype, h.id as hid, '' as jo_category, 'sales' as trantype, DATE_FORMAT(d.delivery_date,'%H:%i:%s') as timeneeded, DATE_FORMAT(d.delivery_date, '%Y-%m-%d') as dateneeded, p.is_misc, p.production_item, h.isConfirm as isConfirm, h.gross_amount as gros, h.forecast_date as forecast_dt, h.delivery_branch as del_branch,p.id as prodid,h.created_at as created
            FROM `ecommerce_sales_details` d
            left join ecommerce_sales_headers h on h.id=d.sales_header_id
            left join products p on p.id=d.product_id
            left join product_categories c on c.id=p.category_id
            left join job_orders jo on jo.sales_detail_id = d.id
            left join branches br on  br.id = jo.pickup_branch
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
            left join users u on u.id = d.created_by
            where h.id>0 and h.delivery_status<>'Open Date' and (h.deleted_at IS NULL OR h.id IS NULL) AND (d.deleted_at IS NULL OR d.id IS NULL) and h.for_deletion = 0 and (jo.deleted_at IS NULL OR jo.id IS NULL) AND (po.deleted_at IS NULL OR po.id IS NULL) and (h.payment_status = 'PAID' OR h.isConfirm=1) AND h.has_sub = 0
            -- and h.id not in (select sales_header_id from product_delivery_addresses)
            ";

            if(isset($_GET['agent']) && $_GET['agent']<>''){
                $qry.= " and h.agent='".$_GET['agent']."'";
            }
            if(isset($_GET['customer']) && $_GET['customer']<>''){
                $qry.= " and h.customer_name='".$_GET['customer']."'";
            }
            if(isset($_GET['product']) && $_GET['product']<>''){
                $qry.= " and d.product_name='".$_GET['product']."'";
            }
            if(isset($_GET['category']) && $_GET['category']<>''){
                $qry.= " and p.category_id='".$_GET['category']."'";
            }
            if(isset($_GET['order_type']) && $_GET['order_type']<>''){
                $qry.= " and h.order_type='".$_GET['order_type']."'";
            }
            if(isset($_GET['order_source']) && $_GET['order_source']<>''){
                $qry.= " and h.order_source='".$_GET['order_source']."'";
                $no_jo = 1;
            }
            if(isset($_GET['production_branch']) && $_GET['production_branch']<>''){
                $qry.= " and pb.id='".$_GET['production_branch']."'";
            }

            // $qry.= " and pb.name='Tandang Sora'";
            
            if (isset($_GET['receiver']) && $_GET['receiver'] <> '') {
                $br_opts = "(";
                $id_opts = "(";
                $bIds = [];

                foreach ($_GET['receiver'] as $re) {
                    $br = \App\EcommerceModel\Branch::whereId($re)->first();

                    if ($br) {
                        $br_opts .= "'" . $br->name . "',";
                        $id_opts .= $re . ",";
                        $bIds[] = $br->id;
                    }
                }

                $br_opts = rtrim($br_opts, ",") . ")";
                $id_opts = rtrim($id_opts, ",") . ")";

                $qry .= " AND (";

                $conditions = [];

                $conditions[] = "
                    (h.delivery_type = 'Store Pickup' AND h.customer_delivery_adress IN $br_opts)
                    OR
                    (jo.pickup_branch IN $id_opts OR h.delivery_branch IN $br_opts)
                ";

                if (in_array(29, $bIds)) {
                    $conditions[] = "
                        (h.order_source = 'Web'
                        AND h.delivery_type = 'Store Pickup'
                        AND h.id NOT IN (
                            SELECT sales_header_id FROM product_delivery_addresses
                        ))
                    ";
                }

                if (in_array(36, $bIds)) {
                    $conditions[] = "
                        (h.order_source = 'Web'
                        AND h.delivery_type = 'Door to door delivery'
                        AND h.id NOT IN (
                            SELECT sales_header_id FROM product_delivery_addresses
                        ))
                    ";
                }

                $qry .= implode(" OR ", $conditions);

                $qry .= ")";

            } else {
                $qry .= "and h.id not in (select sales_header_id from product_delivery_addresses)";
            }



            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry.= " and d.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and d.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
            }
            else{
                $qry.= " and d.delivery_date >='2051-01-01 00:00:00.000' and d.delivery_date <='2051-01-01 23:59:59.999'";
            }
            if(isset($_GET['start_time']) && $_GET['start_time']<>''){ 
                $qry.= " and time(d.delivery_date)='".$_GET['start_time']."'";
            }
            
            if(isset($_GET['item_type']) && count($_GET['item_type']) >= 1){
                if(in_array("WRA",$_GET['item_type']) || in_array("Miscellaneous",$_GET['item_type'])){

                    if(in_array("WRA",$_GET['item_type']) && in_array("Miscellaneous",$_GET['item_type'])){
                        $qry.= " and (p.id in ".$wra." or p.is_misc=1)";
                    }
                    else{
                        if(in_array("WRA",$_GET['item_type'])){
                            $qry.= " and p.id in ".$wra;
                        }
                        if(in_array("Miscellaneous",$_GET['item_type'])){
                            $qry.= " and p.is_misc=1";
                        }
                    }

                }
                else{
                    $qry.= " and d.id=-10011000";   
                }
                
            }
            
            $qry.= " order by d.delivery_date,customer_name,order_number";
            
            $rs = DB::select($qry);
            // logger($qry);
        // Sales

        // Sales with multiple deliveries
            DB::statement("DROP TEMPORARY TABLE IF EXISTS temp_mrs");
            DB::statement("CREATE TEMPORARY TABLE temp_mrs ( 
                    `id` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `product_id` bigint(20) DEFAULT NULL,
                    `product_name` varchar(191) DEFAULT NULL,
                    `price` decimal(15,2) DEFAULT NULL,
                    `address` varchar(191) DEFAULT NULL,
                    `contact_person` varchar(191) DEFAULT NULL,
                    `contact_tel` varchar(191) DEFAULT NULL, 
                    `qty` varchar(191) DEFAULT NULL,
                    `delivery_date` date DEFAULT NULL,
                    `delivery_time` varchar(191) DEFAULT NULL,
                    `delivery_status` varchar(191) DEFAULT NULL,
                    `delivery_fee` decimal(8,2)  NULL DEFAULT 0.00,
                    `location` varchar(191) DEFAULT NULL,
                    `branch` varchar(191) DEFAULT NULL,
                    `note` text DEFAULT NULL,
                    `sales_header_id` bigint(20) DEFAULT NULL,         
                    `paella_price` decimal(10,2) NOT NULL DEFAULT 0.00,
                    `paella` int(11) NOT NULL DEFAULT 0
                    )

            ");
            $tm_st = '2051-01-01 00:00:00.000';
            $tm_en = '2051-01-01 23:59:59.999';
            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $tm_st= $_GET['startdate'];
                $tm_en= $_GET['enddate'];
            }
            
            
            $tm_mrs=\App\Models\ProductDeliveryAddress::where('delivery_date','>=',date('Y-m-d',strtotime($tm_st)))->where('delivery_date','<=',date('Y-m-d',strtotime($tm_en)))
            //->where('sales_header_id','10845')
            ->get();
            
            $ss = '';
            foreach($tm_mrs as $tm){
                $obj2 = json_decode($tm->products);
                if(count((array)$obj2) > 0){              
                
                    foreach($obj2 as $obj){
                        DB::table('temp_mrs')->insert([
                            'product_id' => $obj->product_id,
                            'product_name' => $obj->product->name,
                            'price' => $obj->product->price,
                            'address' => $tm->address,
                            'contact_person' => $tm->contact_person,
                            'contact_tel' => $tm->contact_tel,
                            'qty' => $obj->qty,
                            'delivery_date' => $tm->delivery_date,
                            'delivery_time' => $tm->delivery_time,
                            'delivery_status' => $tm->delivery_status,
                            'delivery_fee' => $tm->delivery_fee,
                            'location' => $tm->location,
                            'branch' => $tm->branch,
                            'note' => $tm->note,
                            'sales_header_id' => $tm->sales_header_id,
                            'paella_price' => $tm->paella_price,
                            'paella' => isset($obj?->paella) ? $obj->paella : 0,
                        ]);
                    }
                }
            }
            //dd(DB::select("select * from temp_mrs"));
            //    IF(m.delivery_status, "YES", "NO") as delstat,

            $mqry = "SELECT distinct m.product_name, m.paella_price, m.paella, m.contact_person, h.has_sub, m.id as idd,
            m.qty, h.order_number, u.address_street, u.address_municipality, u.address_city, u.address_region,m.price, m.address as customer_delivery_adress, h.parent_sales_header_id,
            h.customer_name, m.branch as mbranch, 
            cast(concat(m.delivery_date, ' ', m.delivery_time) as datetime)  as delivery_date,
            m.note as instruction, po.delivery_date as deldate, h.delivery_type, jo.jo_number, pb.name as pbname, 
        
            IFNULL(NULLIF(m.delivery_status, ''), 'Processing Stock') as delstat,
            h.agent, d.product_name as dproduct_name,
            m.contact_tel as customer_contact_number,'' as dr, m.delivery_fee as delivery_fee_amount, d.price, '' as releasing, h.order_source, br.name as receiver, 
            c.name as catname, u.name as username, jo.jo_order_type,h.order_type as hordertype, h.id as hid, '' as jo_category, 'sales' as trantype, 
            m.delivery_time as timeneeded, m.delivery_date as dateneeded, 
            p.is_misc, p.production_item, h.isConfirm as isConfirm, h.gross_amount as gros, h.forecast_date as forecast_dt, 
            h.delivery_branch as del_branch,p.id as prodid,h.created_at as created
            FROM `temp_mrs` m
            left join `ecommerce_sales_details` d on d.product_id=m.product_id and d.sales_header_id=m.sales_header_id
            left join ecommerce_sales_headers h on h.id=d.sales_header_id
            left join products p on p.id=d.product_id
            left join product_categories c on c.id=p.category_id
            left join job_orders jo on jo.sales_detail_id = d.id
            left join branches br on  br.id = jo.pickup_branch
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
            left join users u on u.id = d.created_by
            where h.id>0 and h.delivery_status<>'Open Date' and h.deleted_at is null and d.deleted_at is null AND h.for_deletion = 0 and jo.deleted_at is null and po.deleted_at is null and (h.payment_status = 'PAID' OR h.isConfirm=1) AND h.has_sub = 0
            and h.id in (select sales_header_id from product_delivery_addresses)
            ";

            if(isset($_GET['agent']) && $_GET['agent']<>''){
                $mqry.= " and h.agent='".$_GET['agent']."'";
            }
            if(isset($_GET['customer']) && $_GET['customer']<>''){
                $mqry.= " and h.customer_name='".$_GET['customer']."'";
            }
            if(isset($_GET['product']) && $_GET['product']<>''){
                $mqry.= " and d.product_name='".$_GET['product']."'";
            }
            if(isset($_GET['category']) && $_GET['category']<>''){
                $mqry.= " and p.category_id='".$_GET['category']."'";
            }
            if(isset($_GET['order_type']) && $_GET['order_type']<>''){
                $mqry.= " and h.order_type='".$_GET['order_type']."'";
            }
            if(isset($_GET['order_source']) && $_GET['order_source']<>''){
                $mqry.= " and h.order_source='".$_GET['order_source']."'";
                
            }
            if(isset($_GET['production_branch']) && $_GET['production_branch']<>''){
                $mqry.= " and pb.id='".$_GET['production_branch']."'";
            }
            
            // $mqry.= " and pb.name='Tandang Sora'";
            
            if(isset($_GET['receiver']) && $_GET['receiver']<>''){
                $br_opts = "(";
                $id_opts = "(";
                foreach($_GET['receiver'] as $re){
                    $br = \App\EcommerceModel\Branch::whereId($re)->first();
                    $br_opts .= "'".$br->name."',";
                    $id_opts .= $re.",";
                }
                $br_opts = rtrim($br_opts,",");
                $id_opts = rtrim($id_opts,",");
                $br_opts .= ")";
                $id_opts .= ")";

                

                $mqry.= " and ((h.delivery_type='Store Pickup' and h.customer_delivery_adress in ".$br_opts.") or 

                (jo.pickup_branch in ".$id_opts." OR h.delivery_branch in ".$br_opts.")

                )";
            }


            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $mqry.= " and m.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and m.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
            }
            else{
                $mqry.= " and m.delivery_date >='2051-01-01 00:00:00.000' and m.delivery_date <='2051-01-01 23:59:59.999'";
            }
            if(isset($_GET['start_time']) && $_GET['start_time']<>''){ 
                $mqry.= " and concat(m.delivery_time,':00')='".$_GET['start_time']."'";
            }
            
            if(isset($_GET['item_type']) && count($_GET['item_type']) >= 1){
                if(in_array("WRA",$_GET['item_type']) || in_array("Miscellaneous",$_GET['item_type'])){

                    if(in_array("WRA",$_GET['item_type']) && in_array("Miscellaneous",$_GET['item_type'])){
                        $mqry.= " and (p.id in ".$wra." or p.is_misc=1)";
                    }
                    else{
                        if(in_array("WRA",$_GET['item_type'])){
                            $mqry.= " and p.id in ".$wra;
                        }
                        if(in_array("Miscellaneous",$_GET['item_type'])){
                            $mqry.= " and p.is_misc=1";
                        }
                    }

                }
                else{
                    $mqry.= " and d.id=-10011000";   
                }
                
            }
            
            $mqry.= " order by m.delivery_date,customer_name,order_number";
            //return $mqry;
            $mrs = DB::select($mqry);
            // logger($mqry);
        // Sales Multiple
        
        // JO
            $jos = "
                SELECT jo.jo_category as product_name, '' as paella_price,'' as hordertype, jo.jo_category as dproduct_name, jo.id as idd,
            jo.qty as qty, '' as order_number, u.address_street, u.address_municipality, u.address_city, u.address_region, jo.price, jo.customer_address as customer_delivery_adress,
            jo.customer_name, jo.date_needed as delivery_date,jo.remarks as instruction, po.delivery_date as deldate,'' as delivery_type, jo.jo_number, pb.name as pbname, jo.created_at as created,

            '' as delstat, '' as agent, '' as customer_contact_number,'' as dr, '0' as delivery_fee_amount,'0' as price, '' as releasing, 'Forecaster' as order_source, br.name as receiver, c.name as catname, u.name as username, jo.jo_order_type, '0' as hid, jo.jo_category as jo_category, 'jo' as trantype, DATE_FORMAT(jo.date_needed,'%H:%i:%s') as timeneeded, DATE_FORMAT(jo.date_needed, '%Y-%m-%d') as dateneeded, p.is_misc, p.production_item, '1' as isConfirm, 0 as gros, '' as forecast_dt, '' as del_branch,p.id as prodid
            from job_orders jo 
            left join branches br on  br.id = jo.pickup_branch
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
            left join products p on p.id=jo.product_id
            left join product_categories c on c.id=p.category_id
            left join users u on u.id = jo.user_id
            where jo.id>0 and jo.deleted_at is null and po.deleted_at is null and (jo.sales_detail_id=0 or jo.sales_detail_id is null)";

            if(isset($_GET['product']) && $_GET['product']<>''){
                $jos.= " and p.name='".$_GET['product']."'";
            }
            if(isset($_GET['category']) && $_GET['category']<>''){
                $jos.= " and p.category_id='".$_GET['category']."'";
            }        
            if(isset($_GET['production_branch']) && $_GET['production_branch']<>''){
                $jos.= " and pb.id='".$_GET['production_branch']."'";
            } 
            
            // $jos.= " and pb.name='Tandang Sora'";

            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $jos.= " and jo.date_needed >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and jo.date_needed <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
            }
            else{
                $jos.= " and jo.date_needed >='2051-01-01 00:00:00.000' and jo.date_needed <='2051-01-01 23:59:59.999'";
            }
            if(isset($_GET['start_time']) && $_GET['start_time']<>''){
                $jos.= " and time(jo.date_needed)='".$_GET['start_time']."'";
            }

            
            if(isset($_GET['receiver']) && $_GET['receiver']<>''){
                $jos.= " and jo.pickup_branch in ".$id_opts."";
            }

            if(isset($_GET['customer']) && $_GET['customer']<>''){
                $jos.= " and jo.id='-1'";
            }

            if($no_jo == 1){
                $jos.= " and jo.id='-1'"; // exclude all jo record
            }

            if(isset($_GET['order_type']) && $_GET['order_type']<>''){
                $jos.= " and jo.id='-1100000'";
            }
            

            if(isset($_GET['item_type']) && count($_GET['item_type']) >= 1){
                $itemtype_sels = "(";
                foreach($_GET['item_type'] as $se){
                    $itemtype_sels .= "'".$se."',";
                }            
                $itemtype_sels = rtrim($itemtype_sels,",").")";
                
                $jos.= " and jo.jo_category in ".$itemtype_sels;
                
            }

            $jos.= " order by jo.date_needed, customer_name,jo_number";
            
            $jo = DB::select($jos);
        // JO
        // dd($jo);
        //$results = collect($jo)->merge(collect($rs));
        // $results = collect($mrs)->merge(collect($jo)->merge(collect($rs)));
        //logger($results);
        $results = collect($mrs)
            ->merge($jo)
            ->merge($rs)
            ->unique(function ($row) {
                return (int)($row->hid ?? 0) === 0
                    ? (($row->product_name ?? '') . '|' . ($row->customer_delivery_adress ?? '') . '|' . ($row->idd ?? ''))
                    : (($row->dproduct_name ?? '') . '|' . ($row->hid ?? '') . '|' . ($row->idd ?? ''));
            })
            ->values();

        $ex_array = ['Pantaga','Display','Alpha Size','Belly Pantaga'];

        $original_results = $results;

        if (isset($_GET['filter']) && $_GET['filter'] == 'whole-lechon') {
            $results = $results
                ->where('isConfirm', 1)
                ->where('is_misc', 0)
                ->where('production_item', 1)
                ->whereNotIn('jo_category', $ex_array)
                ->values();
        } elseif (isset($_GET['filter']) && $_GET['filter'] == 'misc') {
            $results = $results
                ->where('isConfirm', 1)
                ->where('is_misc', 1)
                ->values();
        } elseif (isset($_GET['filter']) && $_GET['filter'] == 'overall-lechon') {
            $results = $results
                ->where('isConfirm', 1)
                ->where('is_misc', 0)
                ->values();
        }

        if (isset($_GET['filter']) && $_GET['filter'] == 'display') {
            $results = $results
                ->where('jo_category', 'Display')
                ->values();
        } elseif (isset($_GET['filter']) && $_GET['filter'] == 'alpha-size') {
            $results = $results
                ->where('jo_category', 'Alpha Size')
                ->values();
        } elseif (isset($_GET['filter']) && $_GET['filter'] == 'belly-pantaga') {
            $results = $results
                ->where('jo_category', 'Belly Pantaga')
                ->values();
        } elseif (isset($_GET['filter']) && $_GET['filter'] == 'pantaga') {
            $results = $results
                ->where('jo_category', 'Pantaga')
                ->values();
        }
        
        //logger($results);
        if(isset($_GET['toexcel']))
            return view('admin.reports.forecaster_excel',compact('rs','jo','results','wra_array','mrs'));
        else
            // return view('admin.reports.forecaster',compact('rs','jo','results','wra_array','mrs'));
            return view('admin.reports.forecaster-test',compact('rs','jo','results','wra_array','mrs', 'original_results'));
    }

    public function sales_payment(Request $request)
    {
        $qry = "SELECT h.*,h.created_at as hcreated,h.id as hid,p.*, h.order_number as hnum,p.id as pid,p.created_at as pcreated
        from ecommerce_sales_payments p
        left join ecommerce_sales_headers h on h.id=p.sales_header_id
        where p.id>0 and p.status<>'CANCELLED' and p.amount>0 AND h.has_sub = 0";

        if(isset($_GET['status']) && $_GET['status']<>''){
            $qry.= " and p.status='".$_GET['status']."'";
        }
        if(isset($_GET['customer']) && $_GET['customer']<>''){
            $qry.= " and h.customer_name='".$_GET['customer']."'";
        }
        if(isset($_GET['payment_type']) && $_GET['payment_type']<>''){
            $qry.= " and p.payment_type='".$_GET['payment_type']."'";
        }
        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and p.payment_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and p.payment_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
        }
        else{
            $qry.= " and p.payment_date >='2050-01-01 00:00:00.000'";
        }
        //return $qry;
        //dd($qry);
        // if(isset($_GET['start_date']) && $_GET['start_date']<>''){
        //     $qry.= " and p.payment_date>='".date('Y-m-d',strtotime($_GET['start_date']))."'";
        // }
        // if(isset($_GET['end_date']) && $_GET['end_date']<>''){
        //     $qry.= " and p.payment_date<='".date('Y-m-d',strtotime($_GET['end_date']))."'";
        // }
        $qry.= " order by p.created_at";
        $rs = DB::select($qry);
        

        return view('admin.reports.sales_payment',compact('rs'));

    }
    public function delivery_report_multiple($id, $address)
    {
        if (!is_numeric($id)) {
            $id = base64_decode($id);
        }
        
        $sales = SalesHeader::where('id',$id)->first();

        if (!$sales) {
            return redirect()->route('sales-transaction.index')->with('error', 'Sales record not found.');
        }

        $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        $salesDetails  = SalesDetail::where('sales_header_id',$id)->get();
        $deliveries    = DeliveryStatus::with('user')->where('order_id',$id)->where('product_delivery_address_id', $address)->get();
        $deliveryAddress = ProductDeliveryAddress::with('product')->where('id', $address)->where('sales_header_id', $sales->id)->first();

        if (!$deliveryAddress) {
            return redirect()->route('sales-transaction.index')->with('error', 'Delivery address not found.');
        }

        $gc = GiftCertificate::where('sales_header_id',$id)->get();

        return view('admin.sales.delivery_receipt_multiple_delivery',compact('sales','salesPayments','salesDetails','deliveries','deliveryAddress','gc'));
    }

    public function delivery_report($id)
    {
        $sales = SalesHeader::whereId($id)->first();

        if ($sales->is_sub) {
            $salesPayments = \App\EcommerceModel\SalesPayment::where('sales_header_id', $sales->parent_sales_header_id)->get();
            $totalPayment = \App\EcommerceModel\SalesPayment::where('sales_header_id', $sales->parent_sales_header_id)->sum('amount');
        } else {
            $salesPayments = \App\EcommerceModel\SalesPayment::where('sales_header_id', $id)->get();
            $totalPayment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$id)->sum('amount');
        }

        $salesDetails = \App\EcommerceModel\SalesDetail::where('sales_header_id',$id)->get();
        $deliveries = \App\EcommerceModel\DeliveryStatus::where('order_id',$id)->get();
        $totalNet = \App\EcommerceModel\SalesHeader::where('id',$id)->sum('net_amount');
        if($totalNet <= $totalPayment)
        $status = 'PAID';
        else $status = 'UNPAID';

       // return view('theme.'.config('app.frontend_template').'.pages.ecommerce.sales_summary',compact('sales','salesPayments','salesDetails','status','deliveries'));

        return view('admin.sales.delivery_receipt',compact('sales','salesPayments','salesDetails','status','deliveries'));

    }
    public function delivery_status(Request $request)
    {
        $qry = "SELECT pb.name as prod_branch, jo.jo_number as jnum, h.*, d.*, 
                    h.created_at as hcreated, h.id as hid, p.category_id, c.name as catname, d.id as did
                FROM ecommerce_sales_details d
                LEFT JOIN ecommerce_sales_headers h ON h.id = d.sales_header_id
                LEFT JOIN products p ON p.id = d.product_id
                LEFT JOIN product_categories c ON c.id = p.category_id
                LEFT JOIN job_orders jo ON jo.sales_detail_id = d.id
                LEFT JOIN production_orders po ON po.joborder_id = jo.id
                LEFT JOIN production_branches pb ON pb.id = po.branch_id
                WHERE h.id > 0 AND h.deleted_at IS NULL AND h.for_deletion = 0 AND h.has_sub = 0 AND d.deleted_at IS NULL";

        $bind = [];

        if ($request->filled('agent'))        { $qry .= " AND h.agent = ?";         $bind[] = $request->agent; }
        if ($request->filled('customer'))     { $qry .= " AND h.customer_name = ?"; $bind[] = $request->customer; }
        if ($request->filled('product'))      { $qry .= " AND d.product_name = ?";  $bind[] = $request->product; }
        if ($request->filled('category'))     { $qry .= " AND p.category_id = ?";   $bind[] = $request->category; }
        if ($request->filled('order_source')) { $qry .= " AND h.order_source = ?";  $bind[] = $request->order_source; }

        $from = $request->filled('startdate')
            ? \Carbon\Carbon::parse($request->startdate)->startOfDay()
            : now()->startOfDay();
        $to   = $request->filled('startdate')
            ? \Carbon\Carbon::parse($request->enddate ?? $request->startdate)->endOfDay()
            : now()->endOfDay();

        $qry .= " AND d.delivery_date BETWEEN ? AND ?";
        $bind[] = $from;
        $bind[] = $to;

        $qry .= " ORDER BY d.delivery_date DESC, d.id ASC";

        $rs = collect(DB::select($qry, $bind))
            ->sortBy(fn($r) => strtotime($r->delivery_date))
            ->unique(fn ($row) => ($row->order_number ?? '') . '|' . ($row->product_name ?? ''))
            ->values();

        return view('admin.reports.delivery_status', compact('rs'));
    }


    public function leftover()
    {
        $qry =  "SELECT d.*,p.name as pname, c.name as cname, b.name as bname
                    FROM `leftovers` d
                    left join products p on p.id=d.product_id
                    left join product_categories c on c.id=p.category_id
                    left join branches b on b.id=d.branch_id
                    where d.id>0
                     ";

        if(isset($_GET['branch']) && strlen($_GET['branch'])>=1){
            $qry.= " and b.id='".$_GET['branch']."'";
        }
        if(isset($_GET['product']) && strlen($_GET['product'])>=1){
            $qry.= " and p.name like '%".$_GET['product']."%'";
        }
        if(isset($_GET['category']) && strlen($_GET['category'])>=1){
            $qry.= " and c.id='".$_GET['category']."'";
        }
        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and d.date>='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and d.date<='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
        }
        else{
            $qry.= " and d.date='".date('Y-m-d')."'";
        }


        $rs = DB::select($qry);

        //Dropdowns
        $branches = Branch::where('status', 1)->get();
        $products = Product::all();
        $categories = ProductCategory::all();
        return view('admin.reports.leftover',compact('rs','branches','products','categories'));

    }

/*
    public function joborder(Request $request)
    {

        $qry = "SELECT po.schedule_type as schedtype,pb.name as prod_branch,jo.jo_number as jnum,h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,d.id as did, h.instruction, h.payment_status, h.order_number as ordnum
            FROM  `ecommerce_sales_details` d
            left join ecommerce_sales_headers h on h.id=d.sales_header_id
            left join products p on p.id=d.product_id
            left join product_categories c on c.id=p.category_id
            left join job_orders jo on jo.sales_detail_id = d.id
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
         where h.id>0 and h.deleted_at is null";
        // conditions
            if(isset($_GET['agent']) && $_GET['agent']<>''){
                $qry.= " and h.agent='".$_GET['agent']."'";
            }
            if(isset($_GET['customer']) && $_GET['customer']<>''){
                $qry.= " and h.customer_name='".$_GET['customer']."'";
            }
            if(isset($_GET['product']) && $_GET['product']<>''){
                $qry.= " and d.product_name='".$_GET['product']."'";
            }
            if(isset($_GET['category']) && $_GET['category']<>''){
                $qry.= " and p.category_id='".$_GET['category']."'";
            }
            if(isset($_GET['order_source']) && $_GET['order_source']<>''){
                $qry.= " and h.order_source='".$_GET['order_source']."'";
            }

            if(isset($_GET['branch']) && $_GET['branch']<>''){
                $qry.= " and (h.order_source='".$_GET['branch']."' OR h.outlet='".$_GET['branch']."')";
            }
            if(isset($_GET['production_branch']) && $_GET['production_branch']<>''){
                $qry.= " and pb.id='".$_GET['production_branch']."'";
            }


            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry.= " and jo.date_needed >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and jo.date_needed <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
            }
        // end conditions
  
        $rs = DB::select($qry);


        return view('admin.reports.joborder',compact('rs'));

    }
*/
    public function joborder(Request $request)
    {

        $qry = "SELECT po.schedule_type as schedtype,pb.name as prod_branch,jo.jo_number as jnum,h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,d.id as did, h.instruction, h.payment_status, h.order_number as ordnum, jo.jo_order_type, jo.date_needed,
            IFNULL(jo.jo_category,'Miscellaneous') as item_type
            FROM  
            job_orders jo 
            left join ecommerce_sales_details d on d.id=jo.sales_detail_id
            left join ecommerce_sales_headers h on h.id=d.sales_header_id
            left join products p on p.id=d.product_id
            left join product_categories c on c.id=p.category_id
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
         where h.id>0 and h.deleted_at is null AND h.for_deletion = 0 and jo.deleted_at is null and po.deleted_at is null and d.deleted_at is null AND h.has_sub = 0";
        // conditions
            if(isset($_GET['agent']) && $_GET['agent']<>''){
                $qry.= " and h.agent='".$_GET['agent']."'";
            }
            if(isset($_GET['customer']) && $_GET['customer']<>''){
                $qry.= " and h.customer_name='".$_GET['customer']."'";
            }
            if(isset($_GET['product']) && $_GET['product']<>''){
                $qry.= " and d.product_name='".$_GET['product']."'";
            }
            if(isset($_GET['category']) && $_GET['category']<>''){
                $qry.= " and p.category_id='".$_GET['category']."'";
            }
            if(isset($_GET['order_source']) && $_GET['order_source']<>''){
                $qry.= " and h.order_source='".$_GET['order_source']."'";
            }

            if(isset($_GET['branch']) && $_GET['branch']<>''){
                $qry.= " and (h.order_source='".$_GET['branch']."' OR h.outlet='".$_GET['branch']."')";
            }
            if(isset($_GET['production_branch']) && $_GET['production_branch']<>''){
                $qry.= " and pb.id='".$_GET['production_branch']."'";
            }


            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry.= " and d.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and d.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
            }
            else{
                $qry.= " and d.delivery_date >='2050-01-01 00:00:00.000' and d.delivery_date <='2050-01-01 23:59:59.999'";
            }
            if(isset($_GET['item_type']) && $_GET['item_type']<>''){
                $qry.= " and IFNULL(jo.jo_category,'Miscellaneous')='".$_GET['item_type']."'";
            }
        // end conditions
       // return $qry;
        $rs = DB::select($qry);

        return view('admin.reports.joborder',compact('rs'));

    }

    public function door2door_report(Request $request)
    {
       
        $qry = "SELECT po.schedule_type as schedtype,pb.name as prod_branch,jo.jo_number as jnum,h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,d.id as did, 
        h.instruction, h.payment_status, h.order_number as ordnum, h.delivery_branch as delbra
            FROM  `ecommerce_sales_details` d
            left join ecommerce_sales_headers h on h.id = d.sales_header_id
            left join products p on p.id=d.product_id
            left join product_categories c on c.id=p.category_id
            left join job_orders jo on jo.sales_detail_id = d.id
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
         where h.delivery_type='Door to door delivery' 
         AND (h.deleted_at is null OR h.id IS NULL) 
         AND h.for_deletion = 0 
         and h.isConfirm=1 
         AND h.has_sub = 0 
         and (d.deleted_at is null OR d.id IS NULL)";

         // $qry = "SELECT po.schedule_type as schedtype,pb.name as prod_branch,j.jo_number as jnum,h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,d.id as did, h.instruction, h.payment_status, h.order_number as ordnum
         //        FROM `production_orders` po
         //            left join production_branches pb on pb.id=p.branch_id
         //            left join job_orders j on j.id=p.joborder_id
         //            left join ecommerce_sales_details d on d.id=j.sales_detail_id
         //            left join ecommerce_sales_headers h on h.id=d.sales_header_id
         //            left join products p on p.id=d.product_id
         //            left join product_categories c on c.id=p.category_id
         //            where j.id>0";
        // conditions
            if(isset($_GET['agent']) && $_GET['agent']<>''){
                $qry.= " and h.agent='".$_GET['agent']."'";
            }
            if(isset($_GET['customer']) && $_GET['customer']<>''){
                $qry.= " and h.customer_name='".$_GET['customer']."'";
            }
            if(isset($_GET['product']) && $_GET['product']<>''){
                $qry.= " and d.product_name='".$_GET['product']."'";
            }
            if(isset($_GET['category']) && $_GET['category']<>''){
                $qry.= " and p.category_id='".$_GET['category']."'";
            }
            if(isset($_GET['order_source']) && $_GET['order_source']<>''){
                $qry.= " and h.order_source='".$_GET['order_source']."'";
            }

            if (isset($_GET['branch']) && $_GET['branch'] !== '') {

                if ($_GET['branch'] === 'Tandang Sora Head Office') {

                    $qry .= " AND (
                        (h.order_source = 'Web' AND h.delivery_type = 'Store Pickup')
                        OR h.order_source = 'Tandang Sora Head Office'
                    )";

                } elseif ($_GET['branch'] === 'Tandang Sora Delivery') {

                    $qry .= " AND (
                        (h.order_source = 'Web' AND h.delivery_type = 'Door to door delivery')
                        OR h.order_source = 'Tandang Sora Delivery'
                    )";

                } else {

                    // Normal case
                    $qry .= " AND h.order_source = '" . $_GET['branch'] . "'";
                }
            }

            if(isset($_GET['order_number']) && $_GET['order_number']<>''){
                $qry.= " and h.order_number like '%".$_GET['order_number']."%'";
            }

            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry.= " and d.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and d.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";


                // $qry.= " and d.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and d.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
            }
            else{
                $qry.= " and d.delivery_date >='2051-01-01 00:00:00.000' and d.delivery_date <='2051-01-01 23:59:59.999'";
            }
        // end conditions

        $rs = DB::select($qry);
        
        $rs = collect($rs)
            ->unique(fn ($row) =>   ($row->product_name ?? '') . '|' . ($row->hid ?? ''))
            ->values();

        return view('admin.reports.door2door',compact('rs'));

    }

    public function productionorders(Request $request)
    {
        $qry =  "SELECT p.*,pb.name as production_name, j.jo_number as jo_number,h.instruction as remarks
                    FROM `production_orders` p
                    left join production_branches pb on pb.id=p.branch_id
                    left join job_orders j on j.id=p.joborder_id
                    left join ecommerce_sales_headers h on h.order_number=j.sales_number
                    where j.id>0  and h.deleted_at is null AND h.for_deletion = 0 AND h.has_sub = 0";

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and p.delivery_date>='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and p.delivery_date<='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
        }
        else{
            $qry.= " and p.delivery_date>='2051-01-01 00:00:00.000' and p.delivery_date<='2051-01-01 23:59:59.999'";
        }

        if(isset($_GET['branch']) && strlen($_GET['branch'])>=1){
            $qry.= " and p.branch_id='".$_GET['branch']."'";
        }


        $rs = DB::select($qry);

        $branches = ProductionBranch::orderBy('name','asc')->get();

        return view('admin.reports.production_orders',compact('rs','branches'));

    }

    public function sales_per_agent(Request $request)
    {
        $params = [];
        $qry = "
            SELECT 
                pb.name AS prod_branch,
                jo.jo_number AS jnum,
                h.id AS hid,
                h.created_at AS hcreated,
                h.agent,
                h.order_number,
                h.customer_name,
                d.id AS did,
                d.product_id,
                d.qty,
                d.price,
                (d.qty * d.price) AS total,
                p.category_id,
                p.name AS product_name,
                c.name AS catname

            FROM ecommerce_sales_headers h
            INNER JOIN ecommerce_sales_details d ON h.id = d.sales_header_id
            LEFT JOIN products p ON p.id = d.product_id
            LEFT JOIN product_categories c ON c.id = p.category_id
            LEFT JOIN job_orders jo ON jo.sales_detail_id = d.id
            LEFT JOIN production_orders po ON po.joborder_id = jo.id
            LEFT JOIN production_branches pb ON pb.id = po.branch_id
            WHERE h.agent IS NOT NULL AND h.deleted_at IS NULL AND h.for_deletion = 0 AND d.deleted_at IS NULL AND h.has_sub = 0
        ";

        if ($request->filled('agent')) {
            if ($request->agent === 'no-agent') {
                $qry .= " AND (h.agent = '' OR h.agent IS NULL)";
            } else {
                $qry .= " AND h.agent = ?";
                $params[] = $request->agent;
            }
        }

        if ($request->filled('startdate') && $request->filled('enddate')) {
            $start = date('Y-m-d 00:00:00', strtotime($request->startdate));
            $end = date('Y-m-d 23:59:59', strtotime($request->enddate));
        } else {
            $start = now()->startOfDay()->format('Y-m-d H:i:s');
            $end = now()->endOfDay()->format('Y-m-d H:i:s');
        }

        $qry .= " AND h.created_at BETWEEN ? AND ?";
        $params[] = $start;
        $params[] = $end;


        $qry .= " ORDER BY h.created_at DESC";
        $rs = DB::select($qry, $params);

        $agents = SalesHeader::whereNotNull('agent')
                    ->where('agent', '<>', '')
                    ->distinct()
                    ->orderBy('agent')
                    ->get(['agent']);

        return view('admin.reports.sales_per_agent', compact('rs', 'agents'));
    }

    public function sales_per_customer(Request $request)
    {
        //dd($_GET);
        $qry = "SELECT pb.name as prod_branch,jo.jo_number as jnum,h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,d.id as did
            FROM `ecommerce_sales_details` d
            left join ecommerce_sales_headers h on h.id=d.sales_header_id
            left join products p on p.id=d.product_id
            left join product_categories c on c.id=p.category_id
            left join job_orders jo on jo.sales_detail_id = d.id
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
         where h.id>0 and h.deleted_at is null AND h.for_deletion = 0 and d.deleted_at is null AND h.has_sub = 0";
        // conditions

            if(isset($_GET['pb']) && $_GET['pb']<>''){
                $qry.= " and h.customer_name='".$_GET['pb']."'";
            }


            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry.= " and h.created_at >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and h.created_at <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
            }
            else{
                 $qry.= " and h.created_at >='2051-01-01 00:00:00.000'";
            }
        // end conditions
            //dd($qry);
        $rs = DB::select($qry);

        $customers = SalesHeader::distinct()->orderBy('customer_name')->get(['customer_name']);

        return view('admin.reports.sales_per_customer',compact('rs','customers'));
    }

    public function forecast(Request $request)
    {
        $qry = "SELECT po.*,jo.*,h.instruction as remarks
                FROM `job_orders` jo
                left join production_orders po on po.joborder_id=jo.id
                left join ecommerce_sales_headers h on h.order_number=jo.sales_number
                where jo.status = 'Active'  and h.deleted_at is null AND h.for_deletion = 0 AND h.has_sub = 0";

        if(isset($_GET['branch']) && strlen($_GET['branch'])>=1){
            $qry.= " and po.branch_id='".$_GET['branch']."'";
        }



        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and po.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and po.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
        }

        $rs = DB::select($qry);

        $branches = Branch::where('status', 1)->orderBy('name','asc')->get();

        return view('admin.reports.forecast',compact('rs','branches'));
    }

    public function payment(Request $request)
    {
        $qry = "SELECT po.*,jo.*,h.instruction as remarks
                FROM `job_orders` jo
                left join production_orders po on po.joborder_id=jo.id
                left join ecommerce_sales_headers h on h.order_number=jo.sales_number
                where jo.status = 'Active'  and h.deleted_at is null AND h.for_deletion = 0";

        if(isset($_GET['branch']) && strlen($_GET['branch'])>=1){
            $qry.= " and po.branch_id='".$_GET['branch']."'";
        }

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and po.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and po.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
        }

        $rs = DB::select($qry);

        $branches = Branch::where('status', 1)->orderBy('name','asc')->get();

        return view('admin.reports.forecast',compact('rs','branches'));
    }
    
    
    // new reports added by ryan 08/05/2021
    public function sales_social(Request $request)
    {
        $qry = "select origin, count(id) total_order,sum(gross_amount) total_revenue from ecommerce_sales_headers where status = 'active' and payment_status = 'PAID' ";

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $startDate = date('Y-m-d',strtotime($_GET['startdate']));
            $endDate = date('Y-m-d',strtotime($_GET['enddate']));

            $qry.= " and created_at >='".$startDate." 00:00:00.000' and created_at <='".$endDate." 23:59:59.999'";
        } else {
            $firstDayOfMonth = new Carbon('first day of this month');

            $startDate = $firstDayOfMonth->format('Y-m-d');
            $endDate   = Carbon::today()->format('Y-m-d');

            $qry.= " and created_at >='".$startDate." 00:00:00.000' and created_at <='".$endDate." 23:59:59.999'";
        }

        $qry .= " group by origin";
        $rs = DB::select($qry);

        return view('admin.reports.sales_per_social_media',compact('rs','startDate','endDate'));
    }

    public function top_agents(Request $request)
    {
        $qry = "select agent, count(id) as total_orders from ecommerce_sales_headers where agent is not null and status = 'active' and payment_status = 'PAID' ";

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $startDate = date('Y-m-d',strtotime($_GET['startdate']));
            $endDate = date('Y-m-d',strtotime($_GET['enddate']));

            $qry.= " and created_at >='".$startDate." 00:00:00.000' and created_at <='".$endDate." 23:59:59.999'";
        } else {
            $firstDayOfMonth = new Carbon('first day of this month');

            $startDate = $firstDayOfMonth->format('Y-m-d');
            $endDate   = Carbon::today()->format('Y-m-d');

            $qry.= " and created_at >='".$startDate." 00:00:00.000' and created_at <='".$endDate." 23:59:59.999'";
        }

        $qry .= " group by agent";
        $rs = DB::select($qry);

        return view('admin.reports.sales_top_agent',compact('rs','startDate','endDate'));
    }
    
    public function guest_orders(Request $request)
    {
        $qry = "select * from users where user_type = 'customer' and email like '%lydias.com%' ";


        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $startDate = date('Y-m-d',strtotime($_GET['startdate']));
            $endDate = date('Y-m-d',strtotime($_GET['enddate']));

            $qry.= " and created_at >='".$startDate." 00:00:00.000' and created_at <='".$endDate." 23:59:59.999'";
        } else {
            $firstDayOfMonth = new Carbon('first day of this month');

            $startDate = $firstDayOfMonth->format('Y-m-d');
            $endDate   = Carbon::today()->format('Y-m-d');

            $qry.= " and created_at >='".$startDate." 00:00:00.000' and created_at <='".$endDate." 23:59:59.999'";
        }
        $rs = DB::select($qry);

        return view('admin.reports.sales_guest_logins',compact('rs','startDate','endDate'));
    }

    public function top_products(Request $request)
    {
        $qry = "select sd.product_name, count(sh.id) total_orders, sum(sd.price*sd.qty) total_sales, sum(sd.qty) total_volume, p.weight from ecommerce_sales_details sd left join ecommerce_sales_headers sh on sh.id = sd.sales_header_id left join products as p on p.id = sd.product_id where sd.deleted_at is null and sh.status = 'active' and sh.payment_status = 'PAID' ";

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $startDate = date('Y-m-d',strtotime($_GET['startdate']));
            $endDate = date('Y-m-d',strtotime($_GET['enddate']));

            $qry.= " and sh.created_at >='".$startDate." 00:00:00.000' and sh.created_at <='".$endDate." 23:59:59.999'";
        } else {
            $firstDayOfMonth = new Carbon('first day of this month');

            $startDate = $firstDayOfMonth->format('Y-m-d');
            $endDate   = Carbon::today()->format('Y-m-d');

            $qry.= " and sh. created_at >='".$startDate." 00:00:00.000' and sh.created_at <='".$endDate." 23:59:59.999'";
        }

        $qry .= " group by sd.product_id order by total_sales desc ";
        $rs = DB::select($qry);

        return view('admin.reports.sales_top_products',compact('rs','startDate','endDate'));
    }

    public function sales_per_branch(Request $request)
    {
        $qry = "select order_source, count(id) total_order,sum(gross_amount) total_revenue from ecommerce_sales_headers where status = 'active' and payment_status = 'PAID' AND has_sub = 0";

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $startDate = date('Y-m-d',strtotime($_GET['startdate']));
            $endDate = date('Y-m-d',strtotime($_GET['enddate']));

            $qry.= " and created_at >='".$startDate." 00:00:00.000' and created_at <='".$endDate." 23:59:59.999'";
        } else {
            $firstDayOfMonth = new Carbon('first day of this month');

            $startDate = $firstDayOfMonth->format('Y-m-d');
            $endDate   = Carbon::today()->format('Y-m-d');

            $qry.= " and created_at >='".$startDate." 00:00:00.000' and created_at <='".$endDate." 23:59:59.999'";
        }

        $qry .= " group by order_source";
        $rs = DB::select($qry);

        return view('admin.reports.sales_per_branch',compact('rs','startDate','endDate'));
    }
    
    public function sales_category(Request $request)
    {
        $qry = "select sd.product_category, pcat.name, sum(sd.price*sd.qty) total_sales, count(sh.id) total_orders, sum(sd.qty) total_volume from ecommerce_sales_details sd left join ecommerce_sales_headers sh on sh.id = sd.sales_header_id left join product_categories as pcat on pcat.id = sd.product_category where sd.deleted_at is null and sh.status = 'active' and sh.payment_status = 'PAID' AND sh.has_sub = 0";

        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $startDate = date('Y-m-d',strtotime($_GET['startdate']));
            $endDate = date('Y-m-d',strtotime($_GET['enddate']));

            $qry.= " and sh.created_at >='".$startDate." 00:00:00.000' and sh.created_at <='".$endDate." 23:59:59.999'";
        } else {
            $firstDayOfMonth = new Carbon('first day of this month');

            $startDate = $firstDayOfMonth->format('Y-m-d');
            $endDate   = Carbon::today()->format('Y-m-d');

            $qry.= " and sh. created_at >='".$startDate." 00:00:00.000' and sh.created_at <='".$endDate." 23:59:59.999'";
        }

        $qry .= " group by sd.product_category";
        $rs = DB::select($qry);

        return view('admin.reports.sales_per_category',compact('rs','startDate','endDate'));
    }
    
    
    public function dispatcher(Request $request)
    {
        /*SELECT h.*,d.*,po.delivery_date as hcreated,h.id as hid,p.category_id,c.name as catname,h.agent,pb.name as pbname, h.delivery_status as delstat,
        po.delivery_date as deldate, h.delivery_type,h.instruction, jo.jo_number,br.name as receiver,p.is_misc,u.name as username, jo.jo_order_type, u.address_street, u.address_municipality, u.address_city, u.address_region, IF(p.is_misc=1, 'Miscellaneous', c.name) as item_type,p.is_misc,p.production_item,*/

        $qry = "SELECT d.product_name, d.paella_price,
        d.qty, h.order_number, u.address_street, u.address_municipality, u.address_city, u.address_region,d.price, h.customer_delivery_adress,
        h.customer_name, d.delivery_date as delivery_date, h.instruction, po.delivery_date as deldate, h.delivery_type, jo.jo_number, pb.name as pbname, h.delivery_status as delstat,h.agent, h.customer_contact_number,'' as dr, h.delivery_fee_amount, d.price, '' as releasing, h.order_source, br.name as receiver, c.name as catname, u.name as username, jo.jo_order_type, IF(p.is_misc=1, 'Miscellaneous', c.name) as item_type, h.id as hid, '' as jo_category, 'sales' as trantype, DATE_FORMAT(d.delivery_date,'%H:%i:%s') as timeneeded, DATE_FORMAT(d.delivery_date, '%Y-%m-%d') as dateneeded, p.is_misc, p.production_item
        FROM `ecommerce_sales_details` d
        left join ecommerce_sales_headers h on h.id=d.sales_header_id
        left join products p on p.id=d.product_id
        left join product_categories c on c.id=p.category_id
        left join job_orders jo on jo.sales_detail_id = d.id
        left join branches br on  br.id = jo.pickup_branch
        left join production_orders po on po.joborder_id = jo.id
        left join production_branches pb on pb.id = po.branch_id
        left join users u on u.id = d.created_by
        where h.id>0 and h.deleted_at is null and jo.deleted_at is null and po.deleted_at is null AND h.for_deletion = 0 and d.deleted_at is null AND h.has_sub = 0";

        if(isset($_GET['agent']) && $_GET['agent']<>''){
            $qry.= " and h.agent='".$_GET['agent']."'";
        }
        if(isset($_GET['customer']) && $_GET['customer']<>''){
            $qry.= " and h.customer_name='".$_GET['customer']."'";
        }
        if(isset($_GET['product']) && $_GET['product']<>''){
            $qry.= " and d.product_name='".$_GET['product']."'";
        }
        if(isset($_GET['category']) && $_GET['category']<>''){
            $qry.= " and p.category_id='".$_GET['category']."'";
        }
        if(isset($_GET['order_source']) && $_GET['order_source']<>''){
            $qry.= " and h.order_source='".$_GET['order_source']."'";
        }
        if(isset($_GET['production_branch']) && $_GET['production_branch']<>''){
            $qry.= " and pb.id='".$_GET['production_branch']."'";
        }
        if(isset($_GET['receiver']) && $_GET['receiver']<>''){
            $br = \App\EcommerceModel\Branch::whereId($_GET['receiver'])->first();
            $qry.= " and ((h.delivery_type='Store Pickup' and h.customer_delivery_adress='".$br->name."') or jo.pickup_branch='".$_GET['receiver']."')";
        }


        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and po.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and po.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
        }
        else{
            $qry.= " and po.delivery_date >='2051-01-01 00:00:00.000' and po.delivery_date <='2051-01-01 23:59:59.999'";
        }
        if(isset($_GET['start_time']) && $_GET['start_time']<>''){ 
            $qry.= " and time(d.delivery_date)='".$_GET['start_time']."'";
        }
        if(isset($_GET['item_type']) && $_GET['item_type']<>''){
            $qry.= " and IF(p.is_misc=1, 'Miscellaneous', c.name)='".$_GET['item_type']."'";
        }
        //return $qry;
        $qry.= " order by d.delivery_date,customer_name,order_number";
        $rs = DB::select($qry);
        //dd($rs);
        // Pantaga created by forecaster
        // SELECT jo.*,p.category_id,c.name as catname,pb.name as pbname, p.name as product_name,po.delivery_date as deldate,br.name as receiver,jo.remarks as joremarks,u.name as username, jo.jo_order_type, u.address_street, u.address_municipality, u.address_city, u.address_region,
        //     IF(p.is_misc=1, 'Miscellaneous', c.name) as item_type,p.is_misc,p.production_item from job_orders jo 

        $jos = "
            SELECT jo.jo_category as product_name, '' as paella_price,
        jo.qty as qty, '' as order_number, u.address_street, u.address_municipality, u.address_city, u.address_region, jo.price, jo.customer_address as customer_delivery_adress,
        jo.customer_name, jo.date_needed as delivery_date,jo.remarks as instruction, po.delivery_date as deldate,'' as delivery_type, jo.jo_number, pb.name as pbname, '' as delstat, '' as agent, '' as customer_contact_number,'' as dr, '0' as delivery_fee_amount,'0' as price, '' as releasing, 'Forecaster' as order_source, br.name as receiver, c.name as catname, u.name as username, jo.jo_order_type, IF(p.is_misc=1, 'Miscellaneous', c.name) as item_type, '0' as hid, jo.jo_category as jo_category, 'jo' as trantype, DATE_FORMAT(jo.date_needed,'%H:%i:%s') as timeneeded, DATE_FORMAT(jo.date_needed, '%Y-%m-%d') as dateneeded, p.is_misc, p.production_item from job_orders jo 
        left join branches br on  br.id = jo.pickup_branch
        left join production_orders po on po.joborder_id = jo.id
        left join production_branches pb on pb.id = po.branch_id
        left join products p on p.id=jo.product_id
        left join product_categories c on c.id=p.category_id
        left join users u on u.id = jo.user_id
        where jo.id>0 and jo.deleted_at is null and po.deleted_at is null and (jo.sales_detail_id=0 or jo.sales_detail_id is null)";

        if(isset($_GET['product']) && $_GET['product']<>''){
            $jos.= " and p.name='".$_GET['product']."'";
        }
        if(isset($_GET['category']) && $_GET['category']<>''){
            $jos.= " and p.category_id='".$_GET['category']."'";
        }        
        if(isset($_GET['production_branch']) && $_GET['production_branch']<>''){
            $jos.= " and pb.id='".$_GET['production_branch']."'";
        }
        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $jos.= " and po.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and po.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
        }
        else{
            $jos.= " and po.delivery_date >='2051-01-01 00:00:00.000' and po.delivery_date <='2051-01-01 23:59:59.999'";
        }
        if(isset($_GET['start_time']) && $_GET['start_time']<>''){
            $jos.= " and time(po.delivery_date)='".$_GET['start_time']."'";
        }
        if(isset($_GET['receiver']) && $_GET['receiver']<>''){
            $jos.= " and jo.pickup_branch='".$_GET['receiver']."'";
        }

        if(isset($_GET['customer']) && $_GET['customer']<>''){
            $jos.= " and jo.id='-1'";
        }
        if(isset($_GET['item_type']) && $_GET['item_type']<>''){
            $jos.= " and IF(p.is_misc=1, 'Miscellaneous', c.name)='".$_GET['item_type']."'";
        }

        $jos.= " order by jo.date_needed, customer_name,jo_number";
        $jo = DB::select($jos);

        //dd($rs);
        //return $jos;
        $results = collect($jo)->merge($rs)->groupBy('customer_name');

        $rs = $results->paginate(20);
 
        //dd($jo);
        //collect($jo)->where('jo_category','=','Order')->where('is_misc','0')->where('production_item','1')->sum('qty')  + collect($rs)->where('is_misc','0')->where('production_item','1')->sum('qty')
        //dd(collect($jo)->where('jo_category','=','Order')->sum('qty')  + collect($rs)->where('is_misc','0')->sum('qty'));
        return view('admin.reports.dispatcher',compact('rs','jo','results'));

    }

    public function delivery_per_production_location(Request $request)
    {

        $rs = '';
        $qry = "SELECT pb.name as prod_branch,jo.jo_number as jnum,h.*,d.*,h.created_at as hcreated,h.id as hid,p.category_id,c.name as catname,d.id as did, h.delivery_status as delstat, h.contact_person
            FROM `ecommerce_sales_details` d
            left join ecommerce_sales_headers h on h.id=d.sales_header_id
            left join products p on p.id=d.product_id
            left join product_categories c on c.id=p.category_id
            left join job_orders jo on jo.sales_detail_id = d.id
            left join production_orders po on po.joborder_id = jo.id
            left join production_branches pb on pb.id = po.branch_id
         where h.id>0 and h.deleted_at is null AND h.for_deletion = 0 and d.deleted_at is null AND h.has_sub = 0";
        // conditions
            if(isset($_GET['pb']) && $_GET['pb']<>''){
                $qry.= " and po.branch_id=".$_GET['pb']."";
            }
            if(isset($_GET['customer']) && $_GET['customer']<>''){
                $qry.= " and h.customer_name='".$_GET['customer']."'";
            }
            if(isset($_GET['product']) && $_GET['product']<>''){
                $qry.= " and d.product_name='".$_GET['product']."'";
            }
            if(isset($_GET['category']) && $_GET['category']<>''){
                $qry.= " and p.category_id='".$_GET['category']."'";
            }
            if(isset($_GET['order_source']) && $_GET['order_source']<>''){
                $qry.= " and h.order_source='".$_GET['order_source']."'";
            }


            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry.= " and po.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))."' and po.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))."'";
            }
            else{
                $qry.= " and po.delivery_date >='".date('Y-m-d 00:00:00')."' and po.delivery_date <='".date('Y-m-d 23:59:59')."'";
            }
        // end conditions
           //dd($qry);
        $rs = DB::select($qry);

        return view('admin.reports.delivery_per_production_location',compact('rs'));

    }

    public function audit_trail_per_user(Request $request){
        // $rs = '';
        // $qry = "SELECT * FROM `cms_activity_logs` where id>0 ";
        // // conditions
        //     if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
        //         $qry .= " and activity_date >= '" . date('Y-m-d 00:00:00', strtotime($_GET['startdate'])) . "' and activity_date <= '" . date('Y-m-d 23:59:59', strtotime($_GET['enddate'])) . "'";
        //     }
        //     else{
        //         $qry.= " and activity_date >='".date('Y-m-d 00:00:00')."' and activity_date <='".date('Y-m-d 23:59:59')."'";
        //     }

        //     if(isset($_GET['pb']) && strlen($_GET['pb'])>=1){
        //         $ex = $_GET['pb'];
        //         $qry.= " and (created_by ='".$ex."')";
        //     }
        //     else{
        //         $qry.= " and created_by ='1111111111111111111111111111111111111'";
        //     }
        //     $qry.=" order by id desc";
        // // end conditions
        //    //dd($qry);
        // $rs = DB::select($qry);

        $start = $request->input('startdate') ?? Carbon::now()->format('Y-m-d');
        $end = $request->input('enddate') ?? Carbon::now()->format('Y-m-d');
        $pb = $request->input('pb') ?? null; 
        
        $rs = ActivityLog::when($start && $end, function ($query) use ($start, $end) {
                        $query->whereBetween('activity_date', [
                            Carbon::parse($start)->startOfDay(),
                            Carbon::parse($end)->endOfDay()
                        ]);
                    })
                    ->when($pb, function ($query) use ($pb) {
                        $query->where('created_by', $pb);
                    })
                    ->orderBy('activity_date', 'desc')
                    ->get();

                    // dd($rs);

        $users = User::where('role_id','<>',env('CUSTOMER_ROLE_ID'))->orderBy('name')->get();
        return view('admin.reports.audit_trail_per_user',compact('rs','users'));
    }

    public function searchUsers(Request $request)
    {
        $search = $request->input('q');

        // $users = User::where('role_id', '<>', env('CUSTOMER_ROLE_ID'))
        //             ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
        //             ->orderBy('name')
        //             ->limit(20)
        //             ->get();
            $users = User::where('role_id', '=', 6)
                     ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
                    ->orderBy('name')
                    ->limit(20)
                    ->get();


        return response()->json([
            'results' => $users->map(fn($user) => [
                'id' => $user->id,
                'text' => $user->name." (".$user->email.")"
            ])
        ]);
    }

    // public function searchCustomers(Request $request)
    // {
    //     $search = $request->input('q');

    //     $users = SalesHeader::where('customer_name', 'like', "%{$search}%")                    
    //                 ->orderBy('name')
    //                 ->limit(20)
    //                 ->get();

    //     return response()->json([
    //         'results' => $users->map(fn($user) => [
    //             'id' => $user->id,
    //             'text' => $user->name
    //         ])
    //     ]);
    // }

    public function audit_trail_per_sales(Request $request){
        $rs = '';
        $qry = "SELECT l.* FROM `cms_activity_logs` l
        left join ecommerce_sales_headers h on h.id=l.reference
        where l.reference REGEXP '^-?[0-9]+$' ";
        // conditions
           
            if(isset($_GET['pb']) && strlen($_GET['pb'])>=1){
              
                $qry.= " and order_number like '%".$_GET['pb']."%'";
            }
            else{
                $qry.= " and h.id ='1'";
            }
            $qry.=" order by l.id desc";
        // end conditions
           //dd($qry);
        $rs = DB::select($qry);
   
        return view('admin.reports.audit_trail_per_sales',compact('rs'));
    }

    public function audit_trail_per_external(Request $request){
        $rs = '';
        $qry = "SELECT * FROM `cms_activity_logs` where id>0 ";
        // conditions
            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry .= " and activity_date >= '" . date('Y-m-d 00:00:00', strtotime($_GET['startdate'])) . "' and activity_date <= '" . date('Y-m-d 23:59:59', strtotime($_GET['enddate'])) . "'";

            }
            else{
                $qry.= " and activity_date >='".date('Y-m-d 00:00:00')."' and activity_date <='".date('Y-m-d 23:59:59')."'";
            }

            if(isset($_GET['pb']) && strlen($_GET['pb'])>=1){
                $ex = explode("|", $_GET['pb']);
                $qry.= " and (created_by ='".$ex[0]."' or  created_by ='".$ex[1]."')";
            }
            else{
                $qry.= " and created_by ='1xcx'";
            }
            $qry.=" order by id desc";
        // end conditions
           //dd($qry);
        $rs = DB::select($qry);
        $users = User::where('role_id','=',env('EXTERNAL_ROLE_ID'))->orderBy('name')->get();
        return view('admin.reports.audit_trail_per_external',compact('rs','users'));
    }

    public function gift_cert(Request $request){
        $rs = '';
        $qry = "SELECT * FROM `gift_certificate` where id>0 ";
        // conditions
            if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
                $qry .= " and 
                (
                (created_at >= '" . date('Y-m-d 00:00:00', strtotime($_GET['startdate'])) . "' and created_at <= '" . date('Y-m-d 23:59:59', strtotime($_GET['enddate'])). "')
                or
                (updated_at >= '" . date('Y-m-d 00:00:00', strtotime($_GET['startdate'])) . "' and updated_at <= '" . date('Y-m-d 23:59:59', strtotime($_GET['enddate'])). "')
                )
                
                 ";

            }
            else{
                $qry.= " and created_at >='".date('Y-m-d 00:00:00')."' and created_at <='".date('Y-m-d 23:59:59')."'";
            }

            if(isset($_GET['status']) && strlen($_GET['status'])>=2){
               
                $qry.= " and status ='".$_GET['status']."'";
            }
            else{
                $qry.= " and status <>'1xcx'";
            }
            $qry.=" order by id desc";
        // end conditions
           //dd($qry);
        $rs = DB::select($qry);
        
        return view('admin.reports.giftcert',compact('rs'));
    }

    public function forecast_report_per_product_type(Request $request){
        $wra="(";
        $wra_array=[];
        $products = Product::where('production_item',1)->where('is_misc',0)->get();
        foreach($products as $p){
            $wra.="'".$p->id."',";
            array_push($wra_array,$p->id);
        }
        $wra = rtrim($wra,",");
        $wra.=")";
        $no_jo = 0;

        $qry = "SELECT d.product_name, d.paella_price,d.id as didi, h.contact_person,
        d.qty, h.order_number, u.address_street, u.address_municipality, u.address_city, u.address_region,d.price, h.customer_delivery_adress,
        h.customer_name, d.delivery_date as delivery_date, h.instruction, po.delivery_date as deldate, h.delivery_type, jo.jo_number, pb.name as pbname, h.delivery_status as delstat,h.agent, h.customer_contact_number,'' as dr, h.delivery_fee_amount, d.price, '' as releasing, h.order_source, br.name as receiver, c.name as catname, u.name as username, jo.jo_order_type,h.order_type as hordertype, h.id as hid, '' as jo_category, 'sales' as trantype, DATE_FORMAT(d.delivery_date,'%H:%i:%s') as timeneeded, DATE_FORMAT(d.delivery_date, '%Y-%m-%d') as dateneeded, p.is_misc, p.production_item, h.isConfirm as isConfirm, h.gross_amount as gros, h.forecast_date as forecast_dt, h.delivery_branch as del_branch,p.id as prodid,h.created_at as created
        FROM `ecommerce_sales_details` d
        left join ecommerce_sales_headers h on h.id=d.sales_header_id
        left join products p on p.id=d.product_id
        left join product_categories c on c.id=p.category_id
        left join job_orders jo on jo.sales_detail_id = d.id
        left join branches br on  br.id = jo.pickup_branch
        left join production_orders po on po.joborder_id = jo.id
        left join production_branches pb on pb.id = po.branch_id
        left join users u on u.id = d.created_by
        where h.id>0 and h.delivery_status<>'Open Date' and h.deleted_at is null AND d.deleted_at is null and h.for_deletion = 0 and jo.deleted_at is null and po.deleted_at is null and (h.payment_status = 'PAID' OR h.isConfirm=1) AND h.has_sub = 0";

        if(isset($_GET['agent']) && $_GET['agent']<>''){
            $qry.= " and h.agent='".$_GET['agent']."'";
        }
        if(isset($_GET['customer']) && $_GET['customer']<>''){
            $qry.= " and h.customer_name='".$_GET['customer']."'";
        }
        if(isset($_GET['product']) && $_GET['product']<>''){
            $qry.= " and d.product_name='".$_GET['product']."'";
        }
        if(isset($_GET['category']) && $_GET['category']<>''){
            $qry.= " and p.category_id='".$_GET['category']."'";
        }
        if(isset($_GET['order_type']) && $_GET['order_type']<>''){
            $qry.= " and h.order_type='".$_GET['order_type']."'";
        }
        if(isset($_GET['order_source']) && $_GET['order_source']<>''){
            $qry.= " and h.order_source='".$_GET['order_source']."'";
            $no_jo = 1;
        }
        if(isset($_GET['production_branch']) && $_GET['production_branch']<>''){
            $qry.= " and pb.id='".$_GET['production_branch']."'";
        }

        //dd($_GET['receiver']);

       if(isset($_GET['receiver']) && $_GET['receiver']<>''){
            $br_opts = "(";
            $id_opts = "(";
            foreach($_GET['receiver'] as $re){
                $br = \App\EcommerceModel\Branch::whereId($re)->first();
                $br_opts .= "'".$br->name."',";
                $id_opts .= $re.",";
            }
            $br_opts = rtrim($br_opts,",");
            $id_opts = rtrim($id_opts,",");
            $br_opts .= ")";
            $id_opts .= ")";

            

            $qry.= " and ((h.delivery_type='Store Pickup' and h.customer_delivery_adress in ".$br_opts.") or 

            (jo.pickup_branch in ".$id_opts." OR h.delivery_branch in ".$br_opts.")

            )";
        }


        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $qry.= " and d.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and d.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
        }
        else{
            $qry.= " and d.delivery_date >='2051-01-01 00:00:00.000' and d.delivery_date <='2051-01-01 23:59:59.999'";
        }
        if(isset($_GET['start_time']) && $_GET['start_time']<>''){ 
            $qry.= " and time(d.delivery_date)='".$_GET['start_time']."'";
        }
       
        if(isset($_GET['item_type']) && count($_GET['item_type']) >= 1){
            if(in_array("WRA",$_GET['item_type']) || in_array("Miscellaneous",$_GET['item_type'])){

                if(in_array("WRA",$_GET['item_type']) && in_array("Miscellaneous",$_GET['item_type'])){
                    $qry.= " and (p.id in ".$wra." or p.is_misc=1)";
                }
                else{
                    if(in_array("WRA",$_GET['item_type'])){
                        $qry.= " and p.id in ".$wra;
                    }
                    if(in_array("Miscellaneous",$_GET['item_type'])){
                        $qry.= " and p.is_misc=1";
                    }
                }

            }
            else{
                $qry.= " and d.id=-10011000";   
            }
            
        }
        //return $qry;
        $qry.= " order by d.delivery_date,customer_name,order_number";
        $rs = DB::select($qry);
       
        $jos = "
            SELECT jo.jo_category as product_name, '' as paella_price,'' as hordertype,jo.id as didi,
        jo.qty as qty, '' as order_number, u.address_street, u.address_municipality, u.address_city, u.address_region, jo.price, jo.customer_address as customer_delivery_adress,
        jo.customer_name, jo.date_needed as delivery_date,jo.remarks as instruction, po.delivery_date as deldate,'' as delivery_type, jo.jo_number, pb.name as pbname, jo.created_at as created,

        '' as delstat, '' as agent, '' as customer_contact_number,'' as dr, '0' as delivery_fee_amount,'0' as price, '' as releasing, 'Forecaster' as order_source, br.name as receiver, c.name as catname, u.name as username, jo.jo_order_type, '0' as hid, jo.jo_category as jo_category, 'jo' as trantype, DATE_FORMAT(jo.date_needed,'%H:%i:%s') as timeneeded, DATE_FORMAT(jo.date_needed, '%Y-%m-%d') as dateneeded, p.is_misc, p.production_item, '1' as isConfirm, 0 as gros, '' as forecast_dt, '' as del_branch,p.id as prodid
        from job_orders jo 
        left join branches br on  br.id = jo.pickup_branch
        left join production_orders po on po.joborder_id = jo.id
        left join production_branches pb on pb.id = po.branch_id
        left join products p on p.id=jo.product_id
        left join product_categories c on c.id=p.category_id
        left join users u on u.id = jo.user_id
        where jo.id>0 and jo.deleted_at is null and po.deleted_at is null and (jo.sales_detail_id=0 or jo.sales_detail_id is null)";

        if(isset($_GET['product']) && $_GET['product']<>''){
            $jos.= " and p.name='".$_GET['product']."'";
        }
        if(isset($_GET['category']) && $_GET['category']<>''){
            $jos.= " and p.category_id='".$_GET['category']."'";
        }        
        if(isset($_GET['production_branch']) && $_GET['production_branch']<>''){
            $jos.= " and pb.id='".$_GET['production_branch']."'";
        }
        if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1){
            $jos.= " and po.delivery_date >='".date('Y-m-d',strtotime($_GET['startdate']))." 00:00:00.000' and po.delivery_date <='".date('Y-m-d',strtotime($_GET['enddate']))." 23:59:59.999'";
        }
        else{
            $jos.= " and po.delivery_date >='2051-01-01 00:00:00.000' and po.delivery_date <='2051-01-01 23:59:59.999'";
        }
        if(isset($_GET['start_time']) && $_GET['start_time']<>''){
            $jos.= " and time(po.delivery_date)='".$_GET['start_time']."'";
        }
        if(isset($_GET['receiver']) && $_GET['receiver']<>''){
            $jos.= " and jo.pickup_branch in ".$id_opts."";
        }

        if(isset($_GET['customer']) && $_GET['customer']<>''){
            $jos.= " and jo.id='-1'";
        }

        if($no_jo == 1){
            $jos.= " and jo.id='-1'"; // exclude all jo record
        }

        if(isset($_GET['order_type']) && $_GET['order_type']<>''){
            $jos.= " and jo.id='-1100000'";
        }
        

        if(isset($_GET['item_type']) && count($_GET['item_type']) >= 1){
            $itemtype_sels = "(";
            foreach($_GET['item_type'] as $se){
                $itemtype_sels .= "'".$se."',";
            }            
            $itemtype_sels = rtrim($itemtype_sels,",").")";
            
            $jos.= " and jo.jo_category in ".$itemtype_sels;
            
        }


        $jos.= " order by jo.date_needed, customer_name,jo_number";
     
        $jo = DB::select($jos);
       
        
        $results = collect($jo)->merge(collect($rs));
        

        return view('admin.reports.forecast_report_per_product_type',compact('rs','jo','results','wra_array'));
    }

    public function pickup_orders_per_branch(Request $request){
        $rs = [];
        return view('admin.reports.pickup_orders_per_branch',compact('rs'));
    }

    public function commissary_production(Request $request){
        $rs = [];
        return view('admin.reports.commissary_production',compact('rs'));
    }

}
