<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\EcommerceModel\SalesDetail;
use \App\EcommerceModel\SalesHeader;
use \App\EcommerceModel\Product;
use \App\Models\Logs;
use App\Models\Role;
use App\Models\UserBranch;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $logs = Logs::where('created_by',Auth::id())->orderBy('id','desc')->paginate(15);

        $cutoffDate = now()->addDays(3)->endOfDay();
        $today = now()->startOfDay();
        $thirtyDaysAgo = now()->subDays(30)->startOfDay();

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
                    // ->paidOnlyForForecasterRole()
                    ->where('has_sub', 0)
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
                    // ->paidOnlyForForecasterRole()
                    ->whereIn('id', $eligible) 
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
                        // ->paidOnlyForForecasterRole()
                        ->where('has_sub', 0)
                        ->when($isDispatcher == true,
                            fn ($q) => $q->where('isConfirm', 1)
                        )
                        ->when($hasBranches && count($locations) > 0,
                            fn ($q) => $q->where(function ($q2) use ($locations) {
                                $q2->whereIn('outlet', $locations)
                                ->orWhereIn('order_source', $locations)
                                ->orWhereIn('delivery_branch', $locations);
                            }),
                            fn ($q) => $q
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
                        ->when($isDispatcher == true,
                            fn ($q) => $q->where('payment_status', '==', 'PAID')->orWhere('isConfirm', 1)
                        );
            }
        }

        $pendingPayments = $model
                ->where(function ($query) use ($today, $cutoffDate, $thirtyDaysAgo) {
                    // unpaid + upcoming deliveries
                    $query->where(function ($q) use ($today, $cutoffDate) {
                        $q->whereRaw("payment_status != 'PAID'")
                        ->whereHas('items', function ($q2) use ($today, $cutoffDate) {
                            $q2->whereNotNull('delivery_date')
                                ->where('delivery_date', '!=', '0000-00-00 00:00:00')
                                ->whereBetween('delivery_date', [$today, $cutoffDate]);
                        });
                    })

                    // Sign-Chit within last 30 days
                    ->orWhere(function ($q) use ($thirtyDaysAgo) {
                        $q->whereRaw("payment_status != 'PAID'")
                        ->whereHas('payments', function ($p) {
                            $p->where('payment_type', 'Sign-Chit');
                        })
                        ->whereHas('items', function ($q2) use ($thirtyDaysAgo) {
                            $q2->whereNotNull('delivery_date')
                            ->where('delivery_date', '!=', '0000-00-00 00:00:00')
                            ->where('delivery_date', '>=', $thirtyDaysAgo);
                        });
                    });
                })
                ->whereNotIn('status', ['ABANDONED', 'CANCELLED'])
                ->orderBy(
                    SalesDetail::selectRaw('MIN(delivery_date)')
                        ->whereColumn('sales_header_id', 'ecommerce_sales_headers.id')
                        ->whereNotNull('delivery_date')
                        ->where('delivery_date', '!=', '0000-00-00 00:00:00'),
                    'asc'
                )->paginate(10);

            $tomorrow = now()->addDay()->startOfDay();
            $endTomorrow = now()->addDay()->endOfDay();

            $tomorrowUnpaid = $model->whereHas('items', function ($q) use ($tomorrow, $endTomorrow) {
                        $q->whereBetween('delivery_date', [$tomorrow, $endTomorrow]);
                    })
                    ->whereNotIn('status', ['ABANDONED', 'CANCELLED'])
                    ->get()
                    ->filter(function ($sale) {
                            $itemTotal = $sale->items->sum('net_amount');
                            $deliveryFee = (float) $sale->delivery_fee_amount;

                            $discount = $sale->payments
                                ->where('status', 'PAID')
                                ->where('is_discount', 1)
                                ->sum('amount');

                            $paid = $sale->payments
                                ->where('status', 'PAID')
                                ->where('is_discount', 0)
                                ->sum('amount');

                            $balance = ($itemTotal + $deliveryFee - $discount) - $paid;

                            return $balance > 0;
                        })
                    ->values();
        
        return view('admin.dashboard.index', compact(
            'logs',
            'pendingPayments',
            'tomorrowUnpaid'
        ));
    }
    
    public function ecommerce(Request $request)
    {
        if(isset($_GET['data_from'])){
            if($_GET['data_from'] == 'today'){
                $startDate = Carbon::today()->format('Y-m-d');
                $endDate   = Carbon::today()->format('Y-m-d');
            }

            // if($_GET['data_from'] == 'last_7_days'){
            //     $startDate = Carbon::now()->subDays(7)->format('Y-m-d');
            //     $endDate   = Carbon::today()->format('Y-m-d');
            // }

            if($_GET['data_from'] == 'last_30_days'){
                $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
                $endDate   = Carbon::today()->format('Y-m-d');
            }

            if($_GET['data_from'] == 'last_month'){
                $firstDayLastMonth = new Carbon('first day of last month');
                $lastDayLastMonth  = new Carbon('last day of last month');

                $startDate = $firstDayLastMonth->format('Y-m-d');
                $endDate   = $lastDayLastMonth->format('Y-m-d');
            }

            if($_GET['data_from'] == 'month_to_date'){
                $firstDayOfMonth = new Carbon('first day of this month');

                $startDate = $firstDayOfMonth->format('Y-m-d');
                $endDate   = Carbon::today()->format('Y-m-d');
            }

            if($_GET['data_from'] == 'custom_date'){
                $firstDayOfMonth = new Carbon('first day of this month');

                $startDate = $_GET['startdate'];
                $endDate   = $_GET['enddate'];
            }

        } else {
            $firstDayOfMonth = new Carbon('first day of this month');

            $startDate = $firstDayOfMonth->format('Y-m-d');
            $endDate   = Carbon::today()->format('Y-m-d');
        }


        $qry_product =  "select d.product_id, d.product_name, p.weight, p.price, count(h.id) total_order, sum(d.price*d.qty) total_revenue, sum(d.qty) total_volume from ecommerce_sales_details d left join ecommerce_sales_headers h on h.id = d.sales_header_id left join products p on p.id = d.product_id where h.status = 'active' and h.payment_status = 'PAID' and h.created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and h.created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by d.product_id, d.product_name";


        if(isset($_GET['filter'])){
            if($_GET['filter'] == 'sales'){
                $qry_product .= " order by total_revenue desc limit 10";

                $qry_cat = "select pc.name, d.product_category, sum(d.price*d.qty) filtered_value from ecommerce_sales_details d left join ecommerce_sales_headers h on h.id = d.sales_header_id left join product_categories pc on pc.id = d.product_category where h.status = 'active' ";

                $orders_per_branch = DB::select("select order_source, sum(gross_amount) as filtered_value from ecommerce_sales_headers where status = 'active' and payment_status = 'PAID' and created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by order_source order by filtered_value desc ");

                $top_soc_media = DB::select("select origin, sum(gross_amount) as filtered_value from ecommerce_sales_headers where status = 'active' and payment_status = 'PAID' and created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by origin order by filtered_value desc ");


                $pie_socmed_title = 'Sales by Social Media';
                $pie_branch_title = 'Sales by Branch';
                $pie_ctgory_title = 'Sales by Category';
            }

            if($_GET['filter'] == 'orders'){
                $qry_product .= " order by total_order desc limit 10";

                $qry_cat = "select pc.name, d.product_category, count(h.id) filtered_value from ecommerce_sales_details d left join ecommerce_sales_headers h on h.id = d.sales_header_id left join product_categories pc on pc.id = d.product_category where h.status = 'active' ";

                $orders_per_branch = DB::select("select order_source, count(id) as filtered_value from ecommerce_sales_headers where status = 'active' and payment_status = 'PAID' and created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by order_source order by filtered_value desc ");

                $top_soc_media = DB::select("select origin, count(id) as filtered_value from ecommerce_sales_headers where status = 'active' and payment_status = 'PAID' and created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by origin order by filtered_value desc ");


                $pie_socmed_title = 'Orders by Social Media';
                $pie_branch_title = 'Orders by Branch';
                $pie_ctgory_title = 'Orders by Category';
            }

            if($_GET['filter'] == 'volume'){
                $qry_product .= " order by total_volume desc limit 10";

                $qry_cat = "select pc.name, d.product_category, sum(d.qty) filtered_value from ecommerce_sales_details d left join ecommerce_sales_headers h on h.id = d.sales_header_id left join product_categories pc on pc.id = d.product_category where h.status = 'active' ";

                $orders_per_branch = DB::select("select h.order_source, sum(d.qty) as filtered_value from ecommerce_sales_details d left join ecommerce_sales_headers h on h.id = d.sales_header_id where h.status = 'active' and h.payment_status = 'PAID' and h.created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and h.created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by h.order_source order by filtered_value desc ");

                $top_soc_media = DB::select("select h.origin, sum(d.qty) as filtered_value from ecommerce_sales_details d left join ecommerce_sales_headers h on h.id = d.sales_header_id where h.status = 'active' and h.payment_status = 'PAID' and h.created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and h.created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by h.origin order by filtered_value desc ");


                $pie_socmed_title = 'Order Volume by Social Media';
                $pie_branch_title = 'Order Volume by Branch';
                $pie_ctgory_title = 'Order Volume by Category';
            }
        } else {
            $qry_product .= " order by total_revenue desc limit 10";

            $qry_cat = "select pc.name, d.product_category, sum(d.price*d.qty) filtered_value from ecommerce_sales_details d left join ecommerce_sales_headers h on h.id = d.sales_header_id left join product_categories pc on pc.id = d.product_category where h.status = 'active' ";

            $orders_per_branch = DB::select("select order_source, sum(gross_amount) as filtered_value from ecommerce_sales_headers where status = 'active' and payment_status = 'PAID' and created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by order_source order by filtered_value desc ");

            $top_soc_media = DB::select("select origin, sum(gross_amount) as filtered_value from ecommerce_sales_headers where status = 'active' and payment_status = 'PAID' and created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by origin order by filtered_value desc ");


            $pie_socmed_title = 'Sales by Social Media';
            $pie_branch_title = 'Sales by Branch';
            $pie_ctgory_title = 'Sales by Category';
        }

        $qry_cat .= " and h.payment_status = 'PAID' and h.created_at >='".date('Y-m-d',strtotime($startDate))." 00:00:00.000' and h.created_at <='".date('Y-m-d',strtotime($endDate))." 23:59:59.999' group by d.product_category order by filtered_value desc ";

        $top_selling_categories = DB::select($qry_cat);



        $top_selling_products = DB::select($qry_product);
        $top_prod_names = '';
        $top_prod_earnings = '';
        foreach($top_selling_products as $p){
            $top_prod_names .= str_replace(')',' ',$p->product_name). '('.$p->weight.' ('.number_format($p->price,2).'|';

            if(isset($_GET['filter'])){
                if($_GET['filter'] == 'sales'){
                    $top_prod_earnings .= $p->total_revenue.'|';
                }

                if($_GET['filter'] == 'orders'){
                    $top_prod_earnings .= $p->total_order.'|';
                }

                if($_GET['filter'] == 'volume'){
                    $top_prod_earnings .= $p->total_volume.'|';
                }

            } else {
                $top_prod_earnings .= $p->total_revenue.'|';
            }
        }




        $yr = date("Y",strtotime("-3 year"));
        $arr_yrs = '';
        for ($x = 1; $x <= 3; $x++) {
            $yr++;

            ${"year$x"} = SalesHeader::monthly_sales($yr);
            $arr_yrs .= $yr.'|';
        }


        $arr_months = array();
        for ($i = 0; $i < 8; $i++) {
            $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
            $arr_months[] = date('F', $timestamp);
        }

        $rmonths = array_reverse($arr_months);
        $months = '';
        foreach($rmonths as $mos){
            $months .= $mos.'|';
        }
        
        return view('admin.dashboard.ecom-index',compact('top_selling_products','top_selling_categories','top_soc_media','startDate','endDate','top_prod_names','top_prod_earnings','months','orders_per_branch','year1','year2','year3','arr_yrs','pie_socmed_title','pie_branch_title','pie_ctgory_title'));
    }
}
