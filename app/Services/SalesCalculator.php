<?php

namespace App\Services;

use Illuminate\Support\Collection;

class SalesCalculator
{
    public function calculate(Collection $carts, $deliveryFee = 0, $discount = 0): array
    {
        $gross = 0;

        foreach ($carts as $cart) {
            $price = $cart->product->price ?? $cart->price ?? 0;
            $gross += $price * $cart->qty;
        }

        $total = $gross + $deliveryFee;
        $net = $total - $discount;

        return [
            'gross' => $gross,
            'total' => $total,
            'net' => $net,
            'delivery_fee' => $deliveryFee,
            'discount' => $discount
        ];
    }
}