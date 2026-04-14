<?php

namespace App\Services;

use App\EcommerceModel\Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

class CouponService
{
    public function applyFreeProducts(Collection $carts, $couponData)
    {
        if (!$couponData) return $carts;

        foreach ($couponData as $coupon) {

            if (empty($coupon['free_products'])) continue;

            foreach ($coupon['free_products'] as $freeProduct) {

                $exists = $carts->first(function ($item) use ($freeProduct) {
                    return isset($item->is_free_product)
                        && $item->product_id == $freeProduct['id'];
                });

                if ($exists) continue;

                $cartItem = new Cart();
                $cartItem->product_id = $freeProduct['id'];
                $cartItem->name = $freeProduct['name'];
                $cartItem->price = 0;
                $cartItem->qty = 1;
                $cartItem->is_free_product = true;

                $product = new Fluent($freeProduct);

                $cartItem->setRelation('product', $product);

                $carts->push($cartItem);
            }
        }

        return $carts;
    }
}