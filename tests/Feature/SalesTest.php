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

it('creates guest user', function () {
    $response = $this->postJson('/temp_save', [
        'mobile' => '09123456789',
        'name' => 'John Doe',
        'email' => 'john@test.com',
        'need_date' => now()->addDays(2)->toDateString(),
        'need_time' => '10:00'
    ]);

    expect(User::count())->toBe(1);
});

it('creates sales header', function () {
    $user = User::factory()->create();

    Cart::create([
        'user_id' => $user->id,
        'product_id' => $this->product->id,
        'qty' => 2
    ]);

    $this->actingAs($user);

    $response = $this->postJson('/temp_save', [
        'mobile' => '09123456789',
        'name' => 'John Doe',
        'email' => 'john@test.com',
        'need_date' => now()->addDays(2)->toDateString(),
        'need_time' => '10:00'
    ]);

    $response->assertStatus(200);

    expect(SalesHeader::count())->toBe(1);
});

it('calculates totals correctly', function () {
    $user = User::factory()->create();

    Cart::create([
        'user_id' => $user->id,
        'product_id' => $this->product->id,
        'qty' => 3
    ]);

    $this->actingAs($user);

    $response = $this->postJson('/temp_save', [
        'mobile' => '09123456789',
        'name' => 'John Doe',
        'email' => 'john@test.com',
        'need_date' => now()->addDays(2)->toDateString(),
        'need_time' => '10:00',
        'delivery_fee' => 50
    ]);

    $response->assertJson([
        'amount' => '350.00'
    ]);
});

it('fails if processing hours not met', function () {
    $response = $this->postJson('/temp_save', [
        'mobile' => '09123456789',
        'name' => 'Test',
        'email' => 'test@test.com',
        'need_date' => now()->toDateString(),
        'need_time' => now()->format('H:i')
    ]);

    $response->assertStatus(422);
});