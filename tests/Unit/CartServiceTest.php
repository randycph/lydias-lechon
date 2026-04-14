<?php

use App\Services\CartService;
use App\Models\Product;

it('adds baka service automatically', function () {
    $service = new CartService();

    $product = Product::factory()->create([
        'id' => 178
    ]);

    $bakaService = Product::factory()->create([
        'id' => 270,
        'price' => 3500
    ]);

    $carts = collect([
        (object)[
            'product_id' => 178,
            'qty' => 2,
            'product' => $product
        ]
    ]);

    $result = $service->attachBakaService($carts);

    expect($result->contains(fn($c) => $c->product_id == 270))->toBeTrue();
});