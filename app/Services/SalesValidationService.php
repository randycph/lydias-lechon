<?php

namespace App\Services;

use App\EcommerceModel\Cart;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class SalesValidationService
{
    public function common($request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required','regex:/^(09|\+639)\d{9}$/'],
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    }


    public function singleDeliveriesDatetime($request, $processingService, $minHours)
    {
        if (!$request->has('deliveries') || empty($request->deliveries)) {

            if (!$request->need_date || !$request->need_time) {
                if (!$request->need_date) {
                    return response()->json([
                        'errors' => [
                            'need_date' => ['Date is required']
                        ]
                    ], 422);
                }

                if (!$request->need_time) {
                    return response()->json([
                        'errors' => [
                            'need_time' => ['Time is required']
                        ]
                    ], 422);
                }
            }

            if (!$processingService->validate(
                $request->need_date,
                $request->need_time,
                $minHours
            )) {
                return response()->json([
                    'errors' => [
                        'need_date' => ['Selected date/time does not meet minimum processing hours.']
                    ]
                ], 422);
            }
        }
    }

    public function singleDeliveryLocation($request)
    {
        if (!$request->has('deliveries') && $request->shipping_type == 'delivery') {

            if (!$request->delivery_address)
                return response()->json(['errors' => ['delivery_address' => ['Address is required.']]], 422);

            if (!$request->province)
                return response()->json(['errors' => ['province' => ['Province is required.']]], 422);

            if (!$request->city)
                return response()->json(['errors' => ['city' => ['City is required.']]], 422);

            if (!$request->location)
                return response()->json(['errors' => ['location' => ['Barangay is required.']]], 422);
        }
    }

    public function isPickup($request)
    {
        if ($request->shipping_type == 'pickup') {
            if (!$request->delivery_branch)
                return response()->json(['errors' => ['delivery_branch' => ['Delivery branch is required.']]], 422);
        }
    }

    public function multiDeliveries($request, $deliveries, $processingService, $minimum_processing_hours, $minimum_processing_hours_misc, $minimum_processing_hours_baka)
    {
        if ($request->has('deliveries')) {

            if (!is_array($deliveries)) {
                return response()->json([
                    'errors' => ['deliveries' => ['Invalid delivery format.']]
                ], 422);
            }

            foreach ($deliveries as $index => $delivery) {

                if (empty($delivery->orders)) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.orders" =>
                                ["Please assign at least one order."]
                        ]
                    ], 422);
                }

                if (empty($delivery->need_time)) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.need_time" =>
                                ["Time is required for delivery ".($index+1)."."]]
                    ], 422);
                }

                if (empty($delivery->need_date)) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.need_date" =>
                                ["Date is required for delivery ".($index+1)."."]]
                    ], 422);
                }   

                if (empty($delivery->address))
                    return response()->json(['errors' => ["deliveries.$index.address" => ["Address is required."]]], 422);

                if (empty($delivery->province))
                    return response()->json(['errors' => ["deliveries.$index.province" => ["Province is required."]]], 422);

                if (empty($delivery->city))
                    return response()->json(['errors' => ["deliveries.$index.city" => ["City is required."]]], 422);

                if (empty($delivery->location))
                    return response()->json(['errors' => ["deliveries.$index.location" => ["Barangay is required."]]], 422);

                if (empty($delivery->name))
                    return response()->json(['errors' => ["deliveries.$index.name" => ["Contact person is required."]]], 422);

                if (empty($delivery->phone)) {
                    return response()->json(['errors' => ["deliveries.$index.phone" => ["Contact number is required."]]], 422);
                }
                    
                if (!preg_match('/^(09|\+639)\d{9}$/', $delivery->phone)) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.phone" => ["Invalid mobile number format."]
                        ]
                    ], 422);
                }

                if (empty($delivery->need_date) || empty($delivery->need_time))
                    return response()->json(['errors' => ["deliveries.$index.need_date" => ["Date and time are required."]]], 422);


                // PROCESSING HOURS PER DELIVERY

                $deliveryMinHours = 0;

                foreach ($delivery->orders as $order) {

                    if (!isset($order->product)) continue;

                    if ($order->product->slug === 'lechon-baka') {
                        $deliveryMinHours = max($deliveryMinHours, $minimum_processing_hours_baka);

                    } elseif ($order->product->is_misc == 1) {
                        $deliveryMinHours = max($deliveryMinHours, $minimum_processing_hours_misc);

                    } elseif ($order->product->category_id == 1) {
                        $deliveryMinHours = max($deliveryMinHours, $minimum_processing_hours);
                    }
                }

                if (!$processingService->validate(
                    $delivery->need_date,
                    $delivery->need_time,
                    $deliveryMinHours
                )) {
                    return response()->json([
                        'errors' => [
                            "deliveries.$index.need_date" =>
                                ["Delivery ".($index+1)." does not meet processing hours requirement."]
                        ]
                    ], 422);
                }
            }
        }
    }

    public function processingTime($request, $processingService, $minHours)
    {
        if ((!$request->need_date || !$request->need_time) && !$request->has('deliveries')) {
            return response()->json([
                'errors' => ['need_date' => ['Date & time required']]
            ], 422);
        }

        if (!$processingService->validate(
            $request->need_date,
            $request->need_time,
            $minHours
        ) && !$request->has('deliveries')) {
            return response()->json([
                'errors' => ['need_date' => ['Processing time not met']]
            ], 422);
        }
    }

    public function noAmount($request)
    {
        if (!$request->deposit) {
            return response()->json([
                'errors' => ['amount' => ['Empty amount.']]
            ], 422);
        }
    }

}