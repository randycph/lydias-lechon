<?php

use App\Models\User;
use App\Models\Product;
use App\EcommerceModel\Cart;
use App\EcommerceModel\SalesHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->product = Product::factory()->create([
        'price' => 100
    ]);
});

it('fails validation when mobile is invalid', function () {
    $response = $this->postJson('/temp_save', [
        'mobile' => '123',
        'name' => 'Test',
        'email' => 'test@test.com'
    ]);

    $response->assertStatus(422);
});

it('fails if no pickup branch for pickup', function () {
    $response = $this->postJson('/temp_save', [
        'mobile' => '09174128391',
        'name' => 'Test Testing',
        'email' => 'evilryok@gmail.com',
        'shipping_type' => 'pickup',
        "need_date" => now()->addDays(6)->format('Y-m-d'),
        "need_time" => "14:00",
    ]);

    $response->assertStatus(422);
});

it('fails if no date need and time for delivery', function () {
    $response = $this->postJson('/temp_save', [
        'mobile' => '09174128391',
        'name' => 'Test Testing',
        'email' => 'evilryok@gmail.com',
        'shipping_type' => 'delivery'
    ]);

    $response->assertStatus(422);
});

it('fails if no address for delivery', function () {
    $response = $this->postJson('/temp_save', [
        'mobile' => '09174128391',
        'name' => 'Test Testing',
        'email' => 'evilryok@gmail.com',
        'shipping_type' => 'delivery',
        "need_date" => now()->addDays(6)->format('Y-m-d'),
        "need_time" => "14:00",
    ]);

    $response->assertStatus(422);
});

it('fails if no processing time not met', function () {
    $response = $this->postJson('/temp_save', [
        'mobile' => '09174128391',
        'name' => 'Test Testing',
        'email' => 'evilryok@gmail.com',
        'shipping_type' => 'delivery',
        "need_date" => "2026-03-19",
        "need_time" => "14:00",
        "delivery_address" => "PANGHULO, MALABON CITY, NCR - THIRD DISTRICT",
        "province" => "NCR - THIRD DISTRICT",
        "city" => "MALABON CITY",
        "location" => "PANGHULO",
        "need_date" => "2026-03-21",
        "need_time" => "15:00",
    ]);

    $response->assertStatus(422);
});

it('fails if no order amount passed', function () {
    $response = $this->postJson('/temp_save', [
        "name" => "Randy Corpuz",
        "mobile" => "09174128392",
        "email" => "evilryok@gmail.com",
        "agent" => "Agent code",
        "shipping_type" => "delivery",
        "coupons" => "[]",
        "coupon_data" => "[]",
        "discount_amount" => 0,
        "isBaka" => 0,
        "lechon_baka_service" => 0,
        "delivery_address" => "PANGHULO, MALABON CITY, NCR - THIRD DISTRICT",
        "province" => "NCR - THIRD DISTRICT",
        "city" => "MALABON CITY",
        "location" => "PANGHULO",
        "need_date" => now()->addDays(6)->format('Y-m-d'),
        "need_time" => "15:00",
        "instruction" => "",  
    ]);

    $response->assertStatus(422);
});

it('creates guest user', function () {
    $this->postJson('/temp_save', [
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
    ]);

    expect(User::count())->toBe(1);
});

