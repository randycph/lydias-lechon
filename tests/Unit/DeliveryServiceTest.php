<?php

use App\EcommerceModel\SalesHeader;

it('creates sub orders for multiple deliveries', function () {

    $response = $this->postJson('/temp_save', [
        'mobile' => '09123456789',
        'name' => 'Test User',
        'email' => 'test@test.com',
        'need_date' => now()->addDays(2)->toDateString(),
        'need_time' => '10:00',
        'deliveries' => json_encode([
            [
                'name' => 'Receiver',
                'phone' => '09123456789',
                'address' => 'Test Address',
                'delivery_fee' => 100,
                'need_date' => now()->addDays(2)->toDateString(),
                'need_time' => '10:00',
                'orders' => []
            ]
        ])
    ]);

    $response->assertStatus(200);

    expect(SalesHeader::whereNotNull('parent_sales_header_id')->count())
        ->toBeGreaterThan(0);
});