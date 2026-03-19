<?php

use App\Services\NotificationService;
use App\Services\PaymentService;

it('completes checkout without sending real email or sms', function () {

    $this->mock(NotificationService::class, function ($mock) {
        $mock->shouldReceive('send')->once();
    });

    $this->mock(PaymentService::class, function ($mock) {
        $mock->shouldReceive('generate')->andReturn([
            'amount' => '10000',
            'signature' => 'test-signature'
        ]);
    });

    $response = $this->postJson('/temp_save', [
        'mobile' => '09123456789',
        'name' => 'Test User',
        'email' => 'test@test.com',
        'need_date' => now()->addDays(2)->toDateString(),
        'need_time' => '10:00',
        'order_amount' => 100
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'signature' => 'test-signature'
        ]);
});