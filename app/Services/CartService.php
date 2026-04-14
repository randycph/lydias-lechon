<?php

namespace App\Services;

use App\EcommerceModel\Cart;
use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    public function getCart($user): Collection
    {
        if (auth()->check()) {
            return Cart::where('user_id', $user->id)
                ->with('product')
                ->get();
        }

        return collect(session('cart', []));
    }

    public function attachBakaService(Collection $carts, $user = null): Collection
    {
        $bakaCart = $carts->firstWhere('product_id', 178);

        if (!$bakaCart) {
            return $carts;
        }

        $bakaQty = $bakaCart->qty ?? $bakaCart['qty'];

        $bakaProduct = Product::find(270);

        if (!$bakaProduct) return $carts;

        if (auth()->check()) {
            Cart::updateOrCreate(
                [
                    'product_id' => 270,
                    'user_id' => $user->id,
                ],
                [
                    'qty' => $bakaQty,
                    'price' => $bakaProduct->price,
                    'photo' => null,
                    'paella_price' => 0
                ]
            );

            return Cart::where('user_id', $user->id)->with('product')->get();
        }

        $newItem = new Cart();
        $newItem->product_id = 270;
        $newItem->qty = $bakaQty;
        $newItem->price = $bakaProduct->price;
        $newItem->product = $bakaProduct;

        $carts->push($newItem);

        session(['cart' => $carts]);

        return $carts;
    }
}