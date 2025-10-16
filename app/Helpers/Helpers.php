<?php

use App\EcommerceModel\DeliveryStatus;
use App\EcommerceModel\JobOrder;
use App\EcommerceModel\SalesDetail;
use App\EcommerceModel\SalesHeader;
use Illuminate\Support\Facades\DB;

if(!function_exists('isImageBroken')) {
    function isImageBroken($imageUrl) {
        $imageUrl = trim($imageUrl);

        $headers = @get_headers($imageUrl, 1);

        if ($headers && strpos($headers[0], '200') !== false) {
            if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image/') === 0) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('format_price')) {
    function format_price($price)
    {
        return '₱' . number_format($price, 2);
    }
}

if (!function_exists('isDispatcher')) {
    function isDispatcher()
    {
        return auth()->user()->role_id == 5;
    }
}

if (!function_exists('isForecaster')) {
    function isForecaster()
    {
        return auth()->user()->role_id == 3;
    }
}

if (!function_exists('highlightPaella')) {
    function highlightPaella($name)
    {
        if (empty($name)) {
            return $name;
        }
        $highlight = 'Boneless with Paella';
        return str_replace($highlight, "<b>{$highlight}</b>", $name);
    }
}

if (!function_exists('hasPealla')) {
    function hasPealla($name)
    {
        if (empty($name)) {
            return false;
        }
        return str_contains($name, 'Paella');
    }
}


if (!function_exists('unreadTransactions')) {
    function unreadTransactions(int $days = 1): int
    {
        $from = now()->subDays($days)->startOfDay();

        if (auth()->user()->role_id == 5) {

            $branchId = auth()->user()->role_id == 5 ? auth()->user()->production_branch_id : null;

            $eligible = DB::table('ecommerce_sales_details as d')
                ->join('job_orders as jo', 'jo.sales_detail_id', '=', 'd.id')
                ->join('production_orders as po', 'po.joborder_id', '=', 'jo.id')
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('po.branch_id', $branchId);
                })
                ->select('d.sales_header_id');

            $sales = SalesHeader::where(function ($query) use($eligible) {
                $query->whereIn('id', $eligible)->whereColumn('created_at', 'updated_at')->where('is_new_order', 1);
            });

            return $sales->count();

        } elseif (auth()->user()->role_id == 15) {
            $userName = auth()->user()->id;
            // Step 1: SalesHeader
            $salesHeaders = SalesHeader::with(['user', 'items', 'deliveryAddress', 'deliveryStatuses'])
                ->where('has_transited', 1)
                ->whereHas('deliveryStatuses', function ($q) use ($userName) {
                    $q->where('delivered_by', $userName)
                    ->whereIn('delivery_status', ['In Transit', 'Returned/Rejected', 'Delivered/Picked Up']);
                })
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
                        'updated_at' => $sale->updated_at,
                        'created_at' => $sale->created_at,
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
                        'updated_at' => $job->updated_at,
                        'created_at' => $job->created_at,
                    ];
                });

            // Step 3: Merge collections
            $merged = collect()->merge($salesHeaders)->merge($jobOrders);

            // Step 4: Filter and count unread
            return $merged->filter(function ($item) use ($from) {
                $latestStatus = $item['delivery_status'];
                if (!in_array($latestStatus, ['Delivered/Picked Up', 'Returned/Rejected']) && $item['delivery_type'] != 'Store Pickup') {
                    return true;
                }
                return false;
            })->count();
        } else {
            return SalesHeader::query()
                ->where('is_new_order', 1)
                ->count();
        }
    }
}

if (!function_exists('isUnreadTransaction')) {
    function isUnreadTransaction($transactionId, int $days = 1): int
    {
        if (auth()->user()->role_id == 15) {
            $sales = SalesHeader::with(['deliveryStatuses'])
                ->whereKey($transactionId)
                ->where('has_transited', 1)
                ->first();

            if (!$sales) {
                return false;
            }

            $latestStatus = optional($sales->deliveryStatuses->last())->status;
            if (in_array($latestStatus, ['Delivered/Picked Up', 'Returned/Rejected']) && $sales->delivery_type != 'Store Pickup') {
                return false;
            } else {
                return true;
            }

            // return SalesHeader::query()
            //     ->whereKey($transactionId)
            //     ->where('delivery_type', '!=', 'Store Pickup')
            //     ->whereIn('delivery_status', ['Delivered/Picked Up', 'Returned/Rejected'])
            //     ->exists() ? 1 : 0;
        } else {
            return SalesHeader::query()
                ->whereKey($transactionId)
                ->where('is_new_order', 1)
                ->exists() ? 1 : 0;
        }
    }
}

if (!function_exists('unreadForecastersTransactions')) {
    function unreadForecastersTransactions(int $days = 1): int
    {
        $salesDetails = SalesDetail::whereIN('sales_header_id', function($query){ $query->select('id')->from('ecommerce_sales_headers')->where('status','active')->where('has_sub', 0)->where('isConfirm','1')->whereNull('deleted_at'); } )          
            ->where('Joborder_id',0)
            ->where('delivery_date','>=',date('Y-m-d', strtotime(' - 2 days')))
            ->whereHas('header', function ($q) {
                $q->where('is_new_order', 1);
            })
            ->get();

        return $salesDetails->count();
    }
}