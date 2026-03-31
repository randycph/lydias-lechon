<?php

use App\Services\SalesCalculator;

it('calculates totals correctly', function () {
    $calculator = new SalesCalculator();

    $carts = collect([
        (object)[
            'qty' => 2,
            'product' => (object)['price' => 100]
        ]
    ]);

    $result = $calculator->calculate($carts, 50, 20);

    expect($result['gross'])->toBe(200);
    expect($result['total'])->toBe(250);
    expect($result['net'])->toBe(230);
});