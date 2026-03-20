<?php

use App\Models\User;
use App\EcommerceModel\Cart;
use App\Models\Product;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {

    // Create base product
    $this->product = Product::factory()->create([
        'id' => 251,
        'name' => 'Whole Lechon (X-Large)',
        'price' => 24800,
        'category_id' => 1,
        'slug' => 'whole-lechon-x-large-2-2',
        'is_misc' => 0,
        "uom" => '',
        "size" => ''
    ]);

    // Baka product (178 triggers, 270 service)
    $this->bakaMain = Product::factory()->create([
        'id' => 178,
        'name' => 'Lechon Baka',
        'price' => 5000,
        'category_id' => 1,
        'slug' => 'lechon-baka',
        "uom" => '',
        "size" => ''
    ]);

    $this->bakaService = Product::factory()->create([
        'id' => 270,
        'name' => 'Baka Service Fee',
        'price' => 3500,
        'slug' => 'lechon-baka-service',
        "uom" => '',
        "size" => ''
    ]);
});

function validPayload(array $overrides = [])
{
    return array_merge([
        "name" => "Randy Corpuz",
        "mobile" => "09174128392",
        "email" => "evilryok@gmail.com",
        "agent" => "Agent code",
        "shipping_type" => "pickup",
        "coupons" => "[]",
        "coupon_data" => "[]",
        "discount_amount" => 0,
        "order_amount" => 14800,
        "delivery_fee" => 0,
        "deposit" => "14800.00",
        "total_amount" => 14800,
        "isBaka" => 0,
        "lechon_baka_service" => 0,
        "delivery_branch" => "Cash and Carry, Makati Branch",
        "need_date" => now()->addDays(6)->format('Y-m-d'),
        "need_time" => "14:00",
        "instruction" => "This is note",
    ], $overrides);
}

it('can checkout as guest', function () {

    session([
        'cart' => [
            (object)[
                'product_id' => 1,
                'qty' => 1,
                'price' => 1000,
                'paella_price' => 0,
                'product' => $this->product
            ]
        ]
    ]);

    $response = $this->postJson('/temp_save', validPayload());

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    expect(SalesHeader::count())->toBe(1);
    expect(SalesDetail::count())->toBeGreaterThan(0);
});

it('can checkout as logged in user', function () {

    $user = User::factory()->create();

    Cart::create([
        'user_id' => $user->id,
        'product_id' => 1,
        'qty' => 2,
        'price' => 1000
    ]);

    $this->actingAs($user);

    $response = $this->postJson('/temp_save', validPayload());

    $response->assertStatus(200);

    expect(SalesHeader::count())->toBe(1);
});
