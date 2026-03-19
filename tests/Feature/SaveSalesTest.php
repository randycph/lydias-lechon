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
        'is_misc' => 0
    ]);

    // Baka product (178 triggers, 270 service)
    $this->bakaMain = Product::factory()->create([
        'id' => 178,
        'name' => 'Lechon Baka',
        'price' => 5000,
        'category_id' => 1,
        'slug' => 'lechon-baka',
    ]);

    $this->bakaService = Product::factory()->create([
        'id' => 270,
        'name' => 'Baka Service Fee',
        'price' => 3500,
        'slug' => 'lechon-baka-service'
    ]);
});

function validPayload(array $overrides = [])
{
    return array_merge([
        'name' => 'Test User',
        'mobile' => '09123456789',
        'email' => 'test@example.com',
        'need_date' => now()->addDays(3)->format('Y-m-d'),
        'need_time' => '10:00',
        'order_amount' => 1000,
        'shipping_type' => 'pickup',
        'delivery_branch' => 'Main Branch',
        'deposit' => 500
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

it('fails if mobile is invalid', function () {

    $response = $this->postJson('/temp_save', validPayload([
        'mobile' => '123'
    ]));

    $response->assertStatus(422)
        ->assertJsonStructure(['errors']);
});

it('fails if need_date is missing', function () {

    $payload = validPayload();
    unset($payload['need_date']);

    $response = $this->postJson('/temp_save', $payload);

    $response->assertStatus(422);
});

it('fails if processing time is too soon', function () {

    $response = $this->postJson('/temp_save', validPayload([
        'need_date' => now()->format('Y-m-d'),
        'need_time' => now()->addHour()->format('H:i')
    ]));

    $response->assertStatus(422);
});

it('validates delivery fields when delivery type is delivery', function () {

    $response = $this->postJson('/temp_save', validPayload([
        'shipping_type' => 'delivery'
    ]));

    $response->assertStatus(422)
        ->assertJsonStructure(['errors']);
});

it('can handle multiple deliveries', function () {

    $deliveries = [
        [
            'orders' => [
                [
                    'qty' => 1,
                    'product' => [
                        'id' => 1,
                        'name' => 'Lechon',
                        'price' => 1000,
                        'category_id' => 1
                    ]
                ]
            ],
            'need_date' => now()->addDays(3)->format('Y-m-d'),
            'need_time' => '10:00',
            'address' => 'Test Address',
            'province' => 'Metro Manila',
            'city' => 'Quezon City',
            'location' => 'Diliman',
            'name' => 'Receiver',
            'phone' => '09123456789',
            'delivery_fee' => 100,
            'sms' => true
        ]
    ];

    $response = $this->postJson('/temp_save', validPayload([
        'deliveries' => json_encode($deliveries)
    ]));

    $response->assertStatus(200);

    expect(SalesHeader::count())->toBeGreaterThan(0);
});

it('applies baka service correctly', function () {

    session([
        'cart' => [
            (object)[
                'product_id' => 178,
                'qty' => 1,
                'price' => 5000,
                'product' => $this->bakaMain
            ]
        ]
    ]);

    $response = $this->postJson('/temp_save', validPayload([
        'isBaka' => 1,
        'lechon_baka_service' => 500
    ]));

    $response->assertStatus(200);

    expect(SalesDetail::where('product_id', 178)->exists())->toBeTrue();
});