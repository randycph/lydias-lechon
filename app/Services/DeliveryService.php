<?php

namespace App\Services;

use App\Models\ProductDeliveryAddress;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesDetail;
use App\Helpers\Webfocus\Setting;
use App\Jobs\SendSmsJob;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class DeliveryService
{
    public function handleMultipleDeliveries($deliveries, $salesHeader, $user, $request, $bakaProduct, $qty)
    {
        $ran = microtime();
        $today = getdate();
        $requestId = $today[0].substr($ran, 2,6);

        $delivery_fee = $request->delivery_fee ?? 0;

        $totalPrice = $request->order_amount + ( $bakaProduct->price * $qty );
        $netAmount = $totalPrice + ($request->delivery_fee ?? 0);

        $discount = 0;
        $netAmount = $totalPrice + $delivery_fee;
        $totalPrice = (float) $totalPrice + (float) $delivery_fee;

        $couponsList = json_decode($request->coupons, true);
        if (($couponsList && count($couponsList) > 0) && $request->discount_amount) {
            $discount = (float) $request->discount_amount;
            $netAmount = (float) $totalPrice - (float) $request->discount_amount;
        }
        
        if(Carbon::now()->format('H:i') > Setting::info()?->cutoff){
            $forecast_date = date('Y-m-d', strtotime('+1 days'));
        } else {
            $forecast_date = date('Y-m-d');
        }

        foreach ($deliveries as $k => $delivery) {

            if (empty($delivery->orders)) continue;

            $sub = SalesHeader::create([
                'user_id' => $user->id,
                'parent_sales_header_id' => $salesHeader->id,
                'email' => $request->email ?? $user->email,
                'order_number' => $requestId,
                'customer_name' => $request->name,
                'customer_contact_number' => $delivery->phone,
                'customer_address' => $delivery->address,
                'customer_delivery_adress' => $delivery->address,
                'delivery_tracking_number' => '',
                'delivery_type' => 'Door to door delivery',
                'delivery_fee_amount' => $delivery->delivery_fee,
                'order_source' => 'Web',
                'delivery_branch' => 'Tandang Sora Delivery',
                'gross_amount' => $request->order_amount + ( $bakaProduct->price * $qty ),
                'tax_amount' => 0,
                'net_amount' => $netAmount,
                'discount_amount' => $discount,
                'payment_status' => $request->order_amount <= 0 ? 'PAID' : 'PENDING',
                'delivery_status' => '',
                'status' => 'active',
                'currency' => 'PHP',
                'customer_location' => $request->shipping_type == 'pickup' ? '' : ($request->delivery_address),
                'instruction' => $delivery->note,
                'agent' => $request->agent,
                'contact_person' => $delivery->name,
                'outlet' => $request->shipping_type == 'pickup' ? $request->delivery_branch : '',
                'origin' => $request->hasCookie('origin') ? Cookie::get('origin') : NULL,
                'forecast_date' => $forecast_date,
                'is_multiple_address' => 0,
                'is_new_order' => 1,
                'is_sub' => 1,
                'has_baka' => $delivery?->isBaka ? 1 : 0,
                'lechon_baka_service' => $delivery?->lechon_baka_service ?? 0,
                'city' => $delivery->city ?? '',
                'province' => $delivery->province ?? '',
                'barangay' => $delivery->location ?? '',
            ]);

            if ($request->order_amount <= 0) {
                $sub->isConfirm = 1;
                $sub->confirmed_by = 'Customer';
                $sub->confirmed_on = date('Y-m-d H:i:s');
                $sub->confirm_remarks = 'Auto confirm via Checkout';
                $sub->save();
            }

            // Order number suffix
            $letter = strtoupper(chr(65 + $k));
            $sub->order_number = $salesHeader->order_number . '-' . $letter;
            $sub->save();

            // Save address
            ProductDeliveryAddress::create([
                'sales_header_id' => $sub->id,
                'address' => $delivery->address,
                'contact_person' => $delivery->name,
                'contact_tel' => $delivery->phone,
                'qty' => array_sum(array_column($delivery->orders, 'qty')),
                'location' => $delivery->city . ', ' . $delivery->province,
                'delivery_fee' => $delivery->delivery_fee,
                'delivery_date' => $delivery->need_date,
                'delivery_time' => $delivery->need_time,
                'note' => $delivery->note,
                'branch' => $request->delivery_branch,
                'products' => json_encode($delivery->orders),
                'receive_sms' => $delivery->sms ? 1 : 0,
                'paella_price' =>
                    (isset($delivery->orders[0]->paella) && $delivery->orders[0]->paella === true && !empty($delivery->orders[0]->product->paella_price))
                        ? $delivery->orders[0]->product->paella_price
                        : 0,
                'province' => $delivery->province,
                'city' => $delivery->city,
                'barangay' => $delivery->location ?? '',
                'has_baka' => $delivery?->isBaka ? 1 : 0,
                'lechon_baka_service' => $delivery?->lechon_baka_service ?? 0,
            ]);

            // Save products
            if (isset($delivery->orders) && count($delivery->orders) > 0) {

                $grand_gross = 0;
                $grand_tax = 0;

                foreach ($delivery->orders as $order) {

                    $product = Product::find($order->product_id);
                    $gross_amount = ((float)$product->price + ($order->paella ? $product->paella_price : 0)) * $order->qty;
                    $tax_amount = $gross_amount - ($gross_amount/1.12);
                    $grand_gross += $gross_amount;
                    $grand_tax += $tax_amount;

                    SalesDetail::create([
                        'sales_header_id' => $sub->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name . ($order->paella ? ' Boneless with Paella' : ''),
                        'product_category' => $product->category_id,
                        'price' => $product->price,
                        'cost' => 0,
                        'tax_amount' => $tax_amount,
                        'promo_id' => 0,
                        'promo_description' => '',
                        'discount_amount' => 0,
                        'gross_amount' => $gross_amount,
                        'net_amount' => $gross_amount,
                        'qty' => $order->qty,
                        'paella_qty' => $order->qty,
                        'uom' => $product?->uom ?? "",
                        'size' => $product?->size ?? "",
                        'no_of_pax' => $product->no_of_pax ?? "",
                        'paella_price' => $order->paella ? $product->paella_price : 0,
                        'other_cost' => 0,
                        'other_cost_description' => '',
                        'created_by' => $user->id,
                        'delivery_date' => $delivery->need_date . ' ' . $delivery->need_time,
                        'has_baka' => $delivery?->isBaka ? 1 : 0,
                        'lechon_baka_service' => $delivery?->lechon_baka_service ?? 0,
                    ]);

                    // Baka service
                    if ($product->id == 178 && $bakaProduct) {
                        $product = Product::whereId(270)->first();
                        $gross_amount = ((float)$product->price) * $order->qty;
                        $tax_amount = $gross_amount - ($gross_amount/1.12);
                        $grand_gross += $gross_amount;
                        $grand_tax += $tax_amount;

                        SalesDetail::create([
                            'sales_header_id' => $sub->id,
                            'product_id' => 270,
                            'product_name' => $product->name,
                            'product_category' => $product->category_id,
                            'price' => $product->price,
                            'cost' => 0,
                            'tax_amount' => $tax_amount,
                            'promo_id' => 0,
                            'promo_description' => '',
                            'discount_amount' => 0,
                            'gross_amount' => $gross_amount,
                            'net_amount' => $gross_amount,
                            'qty' => $order->qty,
                            'paella_qty' => 0,
                            'uom' => $product->uom,
                            'size' => $product->size ?? "",
                            'no_of_pax' => $product->no_of_pax ?? "",
                            'paella_price' => 0,
                            'other_cost' => 0,
                            'other_cost_description' => '',
                            'created_by' => $user->id,
                            'delivery_date' => $delivery->need_date . ' ' . $delivery->need_time,
                            'has_baka' => $delivery?->isBaka ? 1 : 0,
                            'lechon_baka_service' => $delivery?->lechon_baka_service ?? 0,
                        ]);
                    }
                }
            }
        }
    }
}