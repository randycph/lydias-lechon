@extends('admin.layouts.app')

@section('pagetitle')
    Order Manager
@endsection

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
        html { overflow: hidden; }

        .coupon-code-label {
            background: #31d7e6;
            color: #004b4f;
            padding: 5px 12px;
            border-radius: 2px;
            font-size: 14px;
            display: inline-block;
            line-height: 1.2;
        }

        .coupon-code-text {
            color: #ff4b00;
            font-size: 11px;
            font-weight: 600;
        }

        .coupon-row td {
            vertical-align: middle;
        }

    </style>
@endsection

@section('content')

    <div class="content ht-100v pd-0">
            <div class="container pd-x-0">
                 <div class="text-center mg-t-20"><img height="100px" src="{{ asset('images/lydias1965.png') }}" alt=""></div>
                <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                    <div>       
                        <h4> <br>Sales Transaction Summary</h4>
                        <h5>Order #: {{$sales->order_number}}</h5>
                        <a href="{{ route('sales.print',$sales->HashOrderNumber) }}" target="_blank" class="btn btn-xs btn-success">Print</a>
                    </div>

                    
                </div>

                @php
                    /*
                    |--------------------------------------------------------------------------
                    | Free product display support
                    |--------------------------------------------------------------------------
                    | In multiple-address orders, the free product can be stored in the
                    | delivery-address JSON while the original Product model still has its
                    | normal price. Use coupon data + line flags to display it as FREE.
                    */
                    $couponRowsForFreeProducts = isset($couponRows) ? collect($couponRows) : collect();

                    $extractFreeProductIds = function ($value) use (&$extractFreeProductIds) {
                        $ids = collect();

                        if ($value === null || $value === '' || $value === []) {
                            return $ids;
                        }

                        if ($value instanceof \Illuminate\Support\Collection) {
                            $value = $value->toArray();
                        }

                        if ($value instanceof \Illuminate\Database\Eloquent\Collection) {
                            $value = $value->toArray();
                        }

                        if (is_object($value)) {
                            $value = (array) $value;
                        }

                        if (is_string($value)) {
                            $text = trim($value);

                            if ($text === '' || in_array(strtolower($text), ['null', 'undefined', '[]', '{}'])) {
                                return $ids;
                            }

                            $decoded = json_decode($text, true);

                            if (json_last_error() === JSON_ERROR_NONE) {
                                return $extractFreeProductIds($decoded);
                            }

                            foreach (preg_split('/[,|]/', $text) as $part) {
                                $part = trim($part);

                                if (is_numeric($part)) {
                                    $ids->push((int) $part);
                                }
                            }

                            return $ids->unique()->values();
                        }

                        if (is_numeric($value)) {
                            return collect([(int) $value]);
                        }

                        if (is_array($value)) {
                            foreach ([
                                'id',
                                'product_id',
                                'productId',
                                'free_product_id',
                                'freeProductId',
                                'reward_product_id',
                                'rewardProductId',
                                'gift_product_id',
                                'giftProductId',
                            ] as $key) {
                                if (!empty($value[$key]) && is_numeric($value[$key])) {
                                    $ids->push((int) $value[$key]);
                                }
                            }

                            foreach ([
                                'product',
                                'free_product',
                                'freeProduct',
                                'reward_product',
                                'rewardProduct',
                                'gift_product',
                                'giftProduct',
                                'free_products',
                                'reward_products',
                                'gift_products',
                            ] as $key) {
                                if (!empty($value[$key])) {
                                    $ids = $ids->merge($extractFreeProductIds($value[$key]));
                                }
                            }

                            foreach ($value as $item) {
                                $ids = $ids->merge($extractFreeProductIds($item));
                            }
                        }

                        return $ids->unique()->values();
                    };

                    $freeProductIds = collect();
                    $couponIds = $couponRowsForFreeProducts
                        ->pluck('coupon_id')
                        ->filter()
                        ->unique()
                        ->values();

                    /*
                     * Resolve the correct Coupon model namespace.
                     * Some Lydia's ecommerce projects use App\EcommerceModel\Coupon,
                     * while newer Laravel projects may use App\Models\Coupon.
                     * This prevents the page from crashing on tickets with coupon rows.
                     */
                    $couponModelClass = null;

                    foreach ([
                        \App\EcommerceModel\Coupon::class,
                        \App\Models\Coupon::class,
                        \App\Coupon::class,
                    ] as $possibleCouponModelClass) {
                        if (class_exists($possibleCouponModelClass)) {
                            $couponModelClass = $possibleCouponModelClass;
                            break;
                        }
                    }

                    if ($couponIds->isNotEmpty() && $couponModelClass) {
                        $couponModelsForFreeProducts = $couponModelClass::whereIn('id', $couponIds)->get();

                        foreach ($couponModelsForFreeProducts as $couponModelForFreeProduct) {
                            $reward = strtolower(trim($couponModelForFreeProduct->reward ?? ''));
                            $isFreeProductCoupon = str_contains($reward, 'free-product') || str_contains($reward, 'free_product');

                            if (!$isFreeProductCoupon) {
                                continue;
                            }

                            foreach ([
                                $couponModelForFreeProduct->free_products ?? null,
                                $couponModelForFreeProduct->free_product ?? null,
                                $couponModelForFreeProduct->free_product_id ?? null,
                                $couponModelForFreeProduct->free_product_ids ?? null,
                                $couponModelForFreeProduct->reward_product ?? null,
                                $couponModelForFreeProduct->reward_products ?? null,
                                $couponModelForFreeProduct->reward_product_id ?? null,
                                $couponModelForFreeProduct->reward_product_ids ?? null,
                                $couponModelForFreeProduct->gift_product_id ?? null,
                                $couponModelForFreeProduct->gift_product_ids ?? null,

                                // fallback for older setups where the free reward item was saved here
                                $couponModelForFreeProduct->product_id ?? null,
                                $couponModelForFreeProduct->product_ids ?? null,
                            ] as $candidate) {
                                $freeProductIds = $freeProductIds->merge($extractFreeProductIds($candidate));
                            }
                        }
                    }

                    $freeProductIds = $freeProductIds->unique()->values();

                    $isFreeProductLine = function ($line) use ($freeProductIds) {
                        $productId = (int) (data_get($line, 'product_id') ?: data_get($line, 'product.id') ?: 0);

                        if ((bool) data_get($line, 'is_free_product', false) === true) {
                            return true;
                        }

                        if ((bool) data_get($line, 'is_free', false) === true) {
                            return true;
                        }

                        if ((bool) data_get($line, 'free_product', false) === true) {
                            return true;
                        }

                        if (!empty(data_get($line, 'coupon_code')) && (float) (data_get($line, 'price') ?? data_get($line, 'product.price') ?? 0) <= 0) {
                            return true;
                        }

                        return $productId > 0 && $freeProductIds->contains($productId);
                    };

                    $displaySalesDetailsGross = collect($salesDetails ?? [])->sum(function ($detail) use ($isFreeProductLine) {
                        return $isFreeProductLine($detail) ? 0 : (float) ($detail->gross_amount ?? 0);
                    });

                    $displaySalesDetailsNet = collect($salesDetails ?? [])->sum(function ($detail) use ($isFreeProductLine) {
                        return $isFreeProductLine($detail) ? 0 : (float) ($detail->net_amount ?? 0);
                    });
                @endphp

                <div class="row row-sm align-items-start">
                    <div class="col-sm-6 col-lg-8">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Customer Details</label>
                        <p class="mg-b-3 tx-semibold">{{$sales->customer_name}}</p>                  
                        <p class="mg-b-3">Mobile No: {{$sales->customer_contact_number ?? $sales->user->contact_mobile }}</p>
                        <p class="mg-b-3">Email: {{$sales->email ?? $sales->user->email}}</p>
                        <p class="mg-b-3">{{$sales->delivery_type}}: 
                            @if ($sales->delivery_type == 'Door to door delivery')
                                @if ($sales?->deliveryAddress && count($sales?->deliveryAddress) > 0)
                                    <ul>
                                    @foreach ($sales->deliveryAddress->sortBy('delivery_date')->sortBy('delivery_time') as $k => $address)
                                    <li>
                                        Contact person: {{ $address->contact_person }}<br>
                                        Contact number: {{ $address->contact_tel }}<br>
                                        Location: {{ $address->location }}<br>
                                        @if ($address->branch)
                                        Delivery Branch: {{ $address->branch ?? '' }}<br>
                                        @endif
                                        Address {{ $k + 1 }}: {{ $address->address }}<br>
                                        {{-- @php
                                            $salesPayments = $sales->payments;
                                        @endphp                              --}}
                                        Payment Method: {{ $salesPayments?->first()?->payment_type ?? 'None' }}<br>
                                        Order/s:
                                            @if ($address->products)
                                                @php
                                                    $products = json_decode($address->products);
                                                @endphp

                                                @if(is_array($products) || is_object($products))
                                                    <table class="table table-invoice bd-b">
                                                        <thead>
                                                        <tr>
                                                            <th class="wd-30p">Product Name</th>                                
                                                            <th class="tx-center">No. of Pax</th>
                                                            <th class="tx-center">Date Needed</th>
                                                            <th class="tx-center">Job Order#</th>
                                                            <th class="tx-center">Quantity</th>
                                                            <th class="tx-center">Paella</th>
                                                            <th class="tx-right">Price</th>
                                                            <th class="tx-right">Total</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @php
                                                            $grandTotal = 0;
                                                        @endphp

                                                        @forelse($products as $product)
                                                            @php
                                                                $prod = \App\Models\Product::find($product->product_id);
                                                                $qty = (int) ($product->qty ?? 1);
                                                                $isFreeProduct = $isFreeProductLine($product);

                                                                $regularPrice = (float) ($prod->price ?? data_get($product, 'product.price', 0));
                                                                $displayPrice = $isFreeProduct ? 0 : $regularPrice;

                                                                $regularPaellaPrice = !empty($product->paella)
                                                                    ? (float) (($prod->paella_price ?? null) ?? data_get($product, 'product.paella_price', 0))
                                                                    : 0;
                                                                $displayPaellaPrice = $isFreeProduct ? 0 : $regularPaellaPrice;

                                                                $lineTotal = ($displayPrice * $qty) + ($displayPaellaPrice * $qty);
                                                                $grandTotal += $lineTotal;
                                                            @endphp
                                                            <tr>
                                                                <td class="tx-nowrap">
                                                                    {!! highlightPaella($product?->product_name ?? $product?->product?->name ?? '') !!}
                                                                    @if($isFreeProduct)
                                                                        <br><small class="tx-success tx-semibold">FREE</small>
                                                                    @endif
                                                                </td>
                                                                <th class="tx-center">{{ $prod->no_of_pax ?? '' }}</th>                                
                                                                <td class="tx-nowrap">
                                                                    {{ \Carbon\Carbon::parse(($address->delivery_date . ' ' . $address->delivery_time))->format('F d, Y g:i A') }}
                                                                </td>
                                                                <td></td>
                                                                <td class="tx-center">{{ number_format($qty, 0) }}</td>
                                                                <td class="tx-right">
                                                                    ₱{{ number_format($displayPaellaPrice, 2) }}
                                                                </td>
                                                                <td class="tx-right">
                                                                    @if($isFreeProduct)
                                                                        <span class="tx-success tx-semibold">FREE</span>
                                                                        @if($regularPrice > 0)
                                                                            <br><small class="text-muted"><s>₱{{ number_format($regularPrice, 2) }}</s></small>
                                                                        @endif
                                                                    @else
                                                                        ₱{{ number_format($displayPrice, 2) }}
                                                                    @endif
                                                                </td>
                                                                <td class="tx-right">
                                                                    ₱{{ number_format($lineTotal, 2) }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="8" class="tx-center">No products</td></tr>
                                                        @endforelse

                                                        @if(isset($address->delivery_fee) && $address->delivery_fee > 0)
                                                            @php
                                                                $grandTotal += $address->delivery_fee;
                                                            @endphp
                                                            <tr>
                                                                <td class="tx-left" colspan="7">Delivery Fee</td>
                                                                <td class="tx-right">₱{{ number_format($address->delivery_fee, 2) }}</td>
                                                            </tr>
                                                        @endif

                                                        <tr>
                                                            <td class="tx-left" colspan="7"><strong>Total</strong></td>
                                                            <td class="tx-right"><strong>₱{{ number_format($grandTotal, 2) }}</strong></td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                @endif
                                            @endif
                                        <br>
                                    </li>
                                    @endforeach
                                    </ul>
                                @else
                                    @php
                                        $region = $sales->region ? $sales->region : '';
                                        $province = $sales->province ? ', ' . $sales->province : '';
                                        $city = $sales->city ? ', ' . $sales->city : '';
                                        $barangay = $sales->barangay ? ', ' . $sales->barangay : '';
                                        $full_address = $sales->customer_delivery_adress ? $sales->customer_delivery_adress . $province . $city . $barangay : '';
                                    @endphp
                                    {{ $full_address }}
                                    @if ($sales->delivery_branch)
                                    <br>Delivery Branch: {{ $sales->delivery_branch ?? '' }}
                                    @endif
                                @endif
                            @else
                                @php
                                    $outlet = trim($sales->outlet ?? '');
                                    // check if $sales->customer_address has a value of , , if so, treat it as empty
                                    $customer_address = trim($sales->customer_address ?? '');
                                    if ($customer_address === ', ,') {
                                        $customer_address = '';
                                    }
                                    $customer_delivery_address = trim($sales->customer_delivery_adress ?? '');
                                    $address_to_use = !empty($outlet) ? $outlet : (!empty($customer_address) ? $customer_address : $customer_delivery_address);
                                @endphp
                                {{ $address_to_use }}
                            @endif
                        </p>
                        @if ($sales?->deliveryAddress && count($sales?->deliveryAddress) == 0)
                            @php 
                                $saleDetail = $sales->items ? $sales->items->first() : null;
                                $deliveryDate = $saleDetail ? \Carbon\Carbon::parse($saleDetail?->delivery_date)->format('F d, Y g:i A') : 'N/A';
                            @endphp
                            @if ($sales->delivery_status <> 'Open Date')
                            <p class="mg-b-3">Date needed: {{$deliveryDate}}</p>
                            @endif
                        @endif

                        <p class="mg-b-3">Contact Person: {{$sales->contact_person ?? $sales->customer_name}}</p>
                        @if ($sales->instruction)
                        <p class="mg-b-3">Note: {{$sales->instruction}}</p>
                        @endif
                    </div>
                    <!-- col -->
                    <div class="col-sm-6 col-lg-4">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Order Details</label>
                        <ul class="list-unstyled lh-7">
                            <li class="d-flex justify-content-between">
                                <span>Order Date</span>
                                <span>{{ \Carbon\Carbon::parse($sales->created_at)->format('F d, Y g:i A') }}</span>
                            </li>                                                   
                            <li class="d-flex justify-content-between">
                                <span>Payment Status</span>
                                <span class="tx-success tx-semibold tx-uppercase">{{$sales->PaymentStatus}}
                                    
                                </span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Delivery Status</span>
                                <span class="tx-success tx-semibold tx-uppercase">{{$sales->delivery_status}}</span>
                            </li>
                            <hr>
                        </ul>
                    </div>
                    <!-- col -->

                    @if (isset($salesDetails) && count($salesDetails) > 0)
                    <div class="table-responsive mg-t-20">
                        <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Order Details</label>
                        <table class="table table-invoice bd-b">
                            
                            <thead>
                            <tr>
                                <th class="wd-10p">Product Code</th>
                                <th class="wd-30p">Product Name</th>                                
                                <th class="tx-center">No. of Pax</th>
                                <th class="tx-center">Date Needed</th>
                                <th class="tx-center">Job Order#</th>
                                <th class="tx-center">Quantity</th>
                                <th class="tx-center">Paella</th>
                                <th class="tx-right">Price</th>
                                <th class="tx-right">Total</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($salesDetails as $details)
                            @php
                                $isFreeDetail = $isFreeProductLine($details);
                                $displayDetailPrice = $isFreeDetail ? 0 : (float) ($details?->price ?? 0);
                                $displayDetailPaellaPrice = $isFreeDetail ? 0 : (float) ($details?->paella_price ?? 0);
                                $displayDetailGross = $isFreeDetail ? 0 : (float) ($details?->gross_amount ?? 0);
                            @endphp
                            <tr>
                                <td class="tx-nowrap">{{$details->product->code}}</td>
                                <td class="tx-nowrap">
                                    {!! highlightPaella($details?->product_name) !!}
                                    @if($isFreeDetail)
                                        <br><small class="tx-success tx-semibold">FREE</small>
                                    @endif
                                </td>
                                <th class="tx-center">{{$details->no_of_pax}}</th>                                
                                <td class="tx-nowrap">
                                    @if ($sales->delivery_status == 'Open Date')
                                        -
                                    @else
                                        @if(date('H:i A',strtotime($details->delivery_date)) == '12:00 PM')
                                            {{ \Carbon\Carbon::parse($details->delivery_date)->format('F d, Y g:i A') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($details->delivery_date)->format('F d, Y g:i A') }}
                                        @endif
                                    @endif
                                    
                                </td>
                                <td>
                                    @forelse($details?->joborders as $jo)
                                        @if($jo->status=='Active')
                                            {{$jo->jo_number}} 
                                            @if($jo?->prodOrder?->joborder_id > 0)
                                                ({{$jo->prodOrder?->prodBranch?->name}})
                                            @endif
                                        @endif
                                    @empty
                                    @endforelse
                                </td>
                                <td class="tx-center">{{number_format($details?->qty, 0)}}</td>
                                <td class="tx-right">₱{{number_format($displayDetailPaellaPrice, 2)}}</td>
                                <td class="tx-right">
                                    @if($isFreeDetail)
                                        <span class="tx-success tx-semibold">FREE</span>
                                        @if((float) ($details?->price ?? 0) > 0)
                                            <br><small class="text-muted"><s>₱{{ number_format($details?->price, 2) }}</s></small>
                                        @endif
                                    @else
                                        ₱{{number_format($displayDetailPrice, 2)}}
                                    @endif
                                </td>
                                <td class="tx-right">₱{{number_format($displayDetailGross, 2)}}</td>                               
                            </tr>
                            @empty
                                <tr>
                                    <td class="tx-center " colspan="9">No transaction found.</td>
                                </tr>
                            @endforelse

                            @php
                                /*
                                 * Multiple-address discount display rule:
                                 * Show the coupon/discount only on the -A transaction.
                                 * This keeps the coupon design but prevents the same discount
                                 * from appearing on every child sales transaction.
                                 */
                                $orderNumberForDiscountDisplay = (string) ($sales->order_number ?? '');
                                $showDiscountOnThisTransaction = \Illuminate\Support\Str::endsWith($orderNumberForDiscountDisplay, '-A');

                                /*
                                 * If this is not a multiple-address child order, keep showing the discount
                                 * for normal/single-address transactions.
                                 */
                                if (!str_contains($orderNumberForDiscountDisplay, '-')) {
                                    $showDiscountOnThisTransaction = true;
                                }

                                $rawCouponRows = isset($couponRows)
                                    ? $couponRows
                                    : ($sales->couponUsed ?? collect());

                                $displayCouponRows = $showDiscountOnThisTransaction
                                    ? $rawCouponRows
                                    : collect();

                                $displayOrderDiscount = $showDiscountOnThisTransaction
                                    ? (float) ($sales->discount_amount ?? 0)
                                    : 0;
                            @endphp

                            @if(isset($displayCouponRows) && count($displayCouponRows) > 0)
                                @foreach($displayCouponRows as $coupon)
                                    @php
                                        $couponCode = $coupon->coupon_code ?? '';

                                        $couponModel = $coupon->coupon ?? null;

                                        $couponName = $coupon->coupon_name
                                            ?? $couponModel?->name
                                            ?? $couponCode
                                            ?? 'Discount';

                                        $rewardType = $couponModel?->reward
                                            ?? $coupon->reward
                                            ?? '';

                                        $rewardLabel = match ($rewardType) {
                                            'free-shipping-optn'        => 'Free Shipping',
                                            'discount-amount-optn'     => 'Discount Amount',
                                            'discount-percentage-optn' => 'Discount Percentage',
                                            'free-product-optn'        => 'Free Product',
                                            default                    => 'Coupon Discount',
                                        };

                                        $discountUsed = (float) ($coupon->discount_used ?? 0);
                                    @endphp

                                    <tr class="coupon-row">
                                        <td class="tx-nowrap">
                                            <span class="coupon-code-label">
                                                {{ !empty($couponCode) ? 'Coupon Code' : 'Discount' }}
                                            </span>
                                            <br>
                                            <small class="coupon-code-text">
                                                {{ !empty($couponCode) ? $couponCode : $couponName }}
                                            </small>
                                        </td>

                                        <td class="tx-nowrap">
                                            <strong>{{ $couponName }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $rewardLabel }}</small>
                                        </td>

                                        <td class="tx-center"></td>
                                        <td class="tx-center"></td>
                                        <td class="tx-center"></td>
                                        <td class="tx-center"></td>
                                        <td class="tx-right"></td>
                                        <td class="tx-right"></td>

                                        <td class="tx-right text-danger">
                                            @if($discountUsed > 0)
                                                -₱{{ number_format($discountUsed, 2) }}
                                            @else
                                                ₱0.00
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            @if($sales->delivery_fee_amount > 0)
                                <tr>
                                    <td class="tx-left " colspan="8">Delivery Fee</td>
                                    <td class="tx-right ">₱{{number_format($sales->delivery_fee_amount, 2)}}</td>
                                </tr>
                            @endif
                            @if($displaySalesDetailsGross > 0 || $sales->delivery_fee_amount > 0)
                                <tr>
                                    <td class="tx-left" colspan="8">Subtotal</td>
                                    <td class="tx-right">₱{{number_format($displaySalesDetailsGross + $sales->delivery_fee_amount, 2)}}</td>
                                </tr>
                            @endif
                            @if($displayOrderDiscount > 0 && (!isset($displayCouponRows) || count($displayCouponRows) == 0))
                            @php
                                $fallbackDiscountName = 'Order Discount';
                                $fallbackRewardLabel  = 'Manual Discount';

                                $discountPayment = $salesPayments
                                    ->where('is_discount', 1)
                                    ->where('status', 'PAID')
                                    ->first();

                                if ($discountPayment) {
                                    $fallbackDiscountName = $discountPayment->payment_type ?? 'Order Discount';
                                    $fallbackRewardLabel  = 'Payment Discount';
                                }
                            @endphp

                            <tr class="coupon-row">
                                <td class="tx-nowrap">
                                    <span class="coupon-code-label">Discount</span>
                                    <br>
                                    <small class="coupon-code-text">{{ $fallbackDiscountName }}</small>
                                </td>

                                <td class="tx-nowrap">
                                    <strong>{{ $fallbackDiscountName }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $fallbackRewardLabel }}</small>
                                </td>

                                <td class="tx-center"></td>
                                <td class="tx-center"></td>
                                <td class="tx-center"></td>
                                <td class="tx-center"></td>
                                <td class="tx-right"></td>
                                <td class="tx-right"></td>

                                <td class="tx-right text-danger">
                                    -₱{{ number_format($displayOrderDiscount, 2) }}
                                </td>
                            </tr>
                        @endif

                                                        @forelse($gc as $g)
                                                            <tr style="font-weight:bold;">
                                                                <td class="tx-left" colspan="8">Gift Certificate: {{$g->code}}</td>
                                                                <td class="tx-right">₱({{number_format($g->amount, 2)}})</td> 
                                                            </tr>
                                                        @empty
                                                        @endforelse
                                                        @if($displaySalesDetailsNet > 0 || $sales->delivery_fee_amount > 0)
                                                            <tr style="font-weight:bold;">
                                                                <td class="tx-left" colspan="8">Total</td>
                                                                <td class="tx-right">₱{{number_format($displaySalesDetailsNet + $sales->delivery_fee_amount - $displayOrderDiscount, 2)}}</td>
                                                            </tr>
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @endif

                                                <div class="table-responsive mg-t-20">
                                                    <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Payments</label>
                                                    <table class="table table-invoice bd-b">
                                                        
                                                        <thead>
                                                        <tr>
                                                            <th class="tx-left">Payment Type</th>
                                                            <th class="tx-center">Receipt No</th>
                                                            <th class="tx-center">Date</th>
                                                            <th class="tx-center">Status</th>
                                                            <th class="tx-right">Amount</th>                                
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $paidTotal=0; @endphp
                                                        @forelse($salesPayments as $payment)   
                                                            
                                                            @php 
                                                                if($payment->status == 'PAID'){
                                                                    if ($payment->is_discount == 1) {
                                                                        $paidTotal-=$payment->amount;
                                                                    } else {
                                                                        $paidTotal+=$payment->amount;
                                                                    }
                                                                }
                                                            @endphp 
                                                        <tr>
                                                            <td class="tx-left">{{$payment->payment_type}}</td>
                                                            <td class="tx-center">{{$payment->receipt_number}}</td>
                                                        <td class="tx-center">{{ !empty($payment->payment_date) ? \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') : '' }}</td>
                                                            <td class="tx-center">
                                                                @if($payment->payment_type == 'Ok Order' || $payment->payment_type == 'COD')
                                                                    @if($payment->status == 'PAID')
                                                                        CONFIRMED
                                                                    @else
                                                                        UNPAID
                                                                    @endif
                                                                @else
                                                                    {{$payment->status}}
                                                                @endif
                                                            </td>
                                                            <td class="tx-right {{ $payment->is_discount == 1 ? 'text-danger' : '' }}">{{ $payment->is_discount == 1 ? '-' : '' }}₱{{number_format($payment->amount, 2)}}</td>
                                                        
                                                        </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="tx-center " colspan="6">No payment found.</td>
                                                            </tr>
                                                        @endforelse
                                                        
                                                        @if($sales->payments->where('status','PAID')->sum('amount') > 0)
                                                            <tr style="font-weight:bold;">
                                                                <td class="tx-left" colspan="4">Total</td>
                                                                <td class="tx-right">₱{{number_format($salesPayments->where('status', '!=', 'CANCELLED')->where('is_discount',0)->sum('amount'), 2)}}</td> 
                                                            </tr>
                                                        @endif
                                                        @php
                                                            /*
                                                             * Balance should follow the same amount shown in the Order Details total.
                                                             * The discount must be deducted before comparing against paid payments,
                                                             * otherwise a fully-paid discounted order shows the discount amount as balance.
                                                             */
                                                            $orderTotalForBalance = max(
                                                                ((float) $displaySalesDetailsNet + (float) ($sales->delivery_fee_amount ?? 0)) - (float) $displayOrderDiscount,
                                                                0
                                                            );

                                                            $paidAmountForBalance = (float) $salesPayments
                                                                ->where('status', 'PAID')
                                                                ->where('is_discount', '!=', 1)
                                                                ->sum('amount');

                                                            $total_balance = max($orderTotalForBalance - $paidAmountForBalance, 0);

                                                            if (strtoupper((string) ($sales->PaymentStatus ?? $sales->payment_status ?? '')) === 'PAID') {
                                                                $total_balance = 0;
                                                            }
                                                        @endphp
                                                        @if($total_balance > 0 && $paidAmountForBalance > 0)
                                                            <tr style="font-style:italic;">
                                                                <td class="tx-left" colspan="4"><br>Balance</td>
                                                                <td class="tx-right"><br>{{ number_format($total_balance, 2) }}</td> 
                                                            </tr>
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <div class="table-responsive mg-t-20">
                                                    <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Delivery History</label>
                                                    <table class="table table-invoice bd-b">
                                                        
                                                        <thead>
                                                        <tr>
                                                            <th class="tx-left">Date</th>
                                                            <th class="tx-center">Status</th>
                                                            <th class="tx-center">Remarks</th>
                                                            <th class="tx-center">Delivered By</th>                              
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                    
                                                        @forelse($deliveries as $delivery)                            
                                                        <tr>
                                                            <td class="tx-left">{{$delivery->created_at}}</td>
                                                            <td class="tx-center">{{$delivery->status}}</td>
                                                            <td class="tx-center">{{$delivery->remarks}}</td>
                                                            <td class="tx-center">{{$delivery->delivered_by}}</td>
                                                        
                                                        </tr>
                                                        @empty
                                                            <tr>
                                                                <td class="tx-center " colspan="6">No delivery transaction found.</td>
                                                            </tr>
                                                        @endforelse
                                                    
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <p>Encoded by: {{$sales->agent ?? ''}}</p>

                                            
                                            
                                                <!-- col -->
                                            

                                            </div>
                                            <!-- row -->
                                        </div>
                                        <!-- container -->
                                    </div>
                            @endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        {{--let searchType = "{{ $searchType }}";--}}
    </script>

{{--    <script src="{{ asset('js/listing.js') }}"></script>--}}
@endsection

@section('customjs')
<script>
    function post_form(id,status,pages){

        $('#posting_form').attr('action',id);
        $('#pages').val(pages);
        $('#status').val(status);
        $('#posting_form').submit();
    }

    {{--function cancel_product(id,status){--}}
    {{--    $('#prompt-cancel-product').modal('show');--}}
    {{--    $('#btnCancelProduct').on('click', function() {--}}
    {{--        //let sales = $('#delivery_status').val();--}}
    {{--        post_form("{{route('sales-transaction.cancel_product')}}",status,id)--}}
    {{--        //console.log(status);--}}
    {{--    });--}}
    {{--}--}}

    $('#prompt-cancel-product').on('show.bs.modal', function (e) {
        //get data-id attribute of the clicked element
        let sales = e.relatedTarget;
        let salesId = $(sales).data('id');
        let salesStatus = $(sales).data('status');
        let formAction = "{{ route('sales-transaction.cancel_product', 0) }}".split('/');
        formAction.pop();
        let editFormAction = formAction.join('/') + "/" + salesId;
        $('#editForm').attr('action', editFormAction);
        $('#id').val(salesId);
        $('#editStatus').val(salesStatus);

    });
</script>
@endsection
