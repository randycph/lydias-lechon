<?php

use App\EcommerceModel\DeliveryStatus;
use App\EcommerceModel\JobOrder;
use App\EcommerceModel\SalesDetail;
use App\EcommerceModel\SalesHeader;
use App\Models\Role;
use App\Models\UserBranch;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $roleId        = auth()->user()->role_id;
        $role          = Role::find($roleId);
        $hasProdBranch = (int) $role->has_production_branch === 1;
        $hasBranches   = (int) $role->has_branches === 1;

        $branches   = UserBranch::accessBranch();
        $locations  = [];
        foreach ($branches as $branch) {
            $locations[] = $branch?->branch?->name ?? $branch?->name ?? null;
        }

        if ($hasProdBranch && auth()->user()->role_id == 5) {

            $branchIds = explode(',', auth()->user()->production_branch_id) ?? null;

            if ($branchIds == null) {
                return 0;
            }
            
            $today = now();

            $eligible = DB::table('ecommerce_sales_details as d')
                ->join('job_orders as jo', 'jo.sales_detail_id', '=', 'd.id')
                ->join('production_orders as po', 'po.joborder_id', '=', 'jo.id')
                ->when($branchIds, function ($query) use ($branchIds) {
                    return $query->whereIn('po.branch_id', $branchIds);
                })
                ->where('d.delivery_date', '>=', $today->startOfDay()->toDateTimeString())
                ->select('d.sales_header_id');

            // $sales = SalesHeader::where(function ($query) use($eligible) {
            //     $query->whereIn('id', $eligible)->where('is_new_order', 1);
            // });

            $sales = SalesHeader::where(function ($query) use($eligible) {
                $query->whereIn('id', $eligible)->where('has_sub', 0)->where('is_new_order', 1)->where('for_deletion', 0);
            });

            return $sales->count();

        } elseif (auth()->user()->role_id == 15) {
            $userName = auth()->user()->id;

            // Step 1: SalesHeader
            $salesHeaders = SalesHeader::with(['user', 'items', 'deliveryAddress', 'deliveryStatuses'])
                ->where('has_transited', 1)
                ->where('for_deletion', 0)
                ->whereHas('deliveryStatuses', function ($q) use ($userName) {
                    $q->where('delivered_by', $userName)
                    ->whereIn('delivery_status', ['In Transit', 'Returned/Rejected', 'Delivered/Picked Up']);
                })
                ->when($locations, function ($query) use ($locations) {
                        $query->whereIn('outlet', $locations)
                        ->orWhereIn('order_source', $locations);
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

            $today = now();

            return SalesHeader::where('id','>',0)
                    ->with('items', function($q) use($today) {
                        $q->where('delivery_date', '>=', $today->startOfDay()->toDateTimeString())
                          ->orderBy('delivery_date', 'asc');
                    })
                    ->where('has_sub', 0)
                    ->where('status', 'active')
                    ->where('for_deletion', 0)
                    ->where('is_new_order', 1)
                    ->count();
        }
    }
}

if (!function_exists('isUnreadTransaction')) {
    function isUnreadTransaction($transactionId, int $days = 1): int
    {
        if (auth()->user()->role_id == 15) {

            $branches   = UserBranch::accessBranch();
            $locations  = [];
            foreach ($branches as $branch) {
                $locations[] = $branch?->branch?->name ?? $branch?->name ?? null;
            }

            $sales = SalesHeader::with(['deliveryStatuses'])
                ->whereKey($transactionId)
                ->when($locations, function ($query) use ($locations) {
                    $query->whereIn('outlet', $locations)
                    ->orWhereIn('order_source', $locations);
                })
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
        $branches   = UserBranch::accessBranch();

        $locations  = [];
        if ($branches) {
            foreach ($branches as $branch) {
                $locations[] = $branch?->branch?->name ?? $branch?->name ?? null;
            }
        }
        $salesDetails = SalesDetail::whereIN('sales_header_id', function($query){ $query->select('id')->from('ecommerce_sales_headers')->where('status','active')->where('has_sub', 0)->where('isConfirm','1')->whereNull('deleted_at'); } )          
            ->where('Joborder_id',0)
            ->where('delivery_date','>=',date('Y-m-d', strtotime(' - 2 days')))
            ->whereHas('header', function ($q) use ($locations) {
                $q->where('is_new_order', 1);
                $q->when($locations, function ($query) use ($locations) {
                    $query->whereIn('outlet', $locations)
                    ->orWhereIn('order_source', $locations);
                });
            })
            ->get();

        return $salesDetails->count();
    }
}

if (!function_exists('canAddPayment')) {

    function canAddPayment(SalesHeader $sale, int $days = 30): bool
    {
        // OVERRIDE via URL
        if (request()->boolean('force_over_30')) {
            $isOverDays = true;
        } else {
            $isOverDays = $sale->created_at->diffInDays(now()) >= $days;
        }

        $hasSignChit = $sale->payments()
            ->where('payment_type', 'Sign-Chit')
            ->exists();

        return !($hasSignChit && $isOverDays);
    }
}

if (!function_exists('safe_date')) {
    function safe_date($value): ?Carbon
    {
        if (empty($value) || $value === '0000-00-00 00:00:00') {
            return null;
        }

        try {
            $date = Carbon::parse($value);

            // Reject Unix epoch fallback
            if ($date->year === 1970) {
                return null;
            }

            return $date;
        } catch (\Exception $e) {
            return null;
        }
    }
}