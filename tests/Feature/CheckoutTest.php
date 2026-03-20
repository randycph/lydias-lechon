<?php

use App\EcommerceModel\SalesHeader;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\PaymentService;
use App\Services\SendNotification;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;

it('creates single pickup transaction', function() {

    $response = $this->postJson('/temp_save', [
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

    logger('pickup', ['response' => $response->json()]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);
});


it('creates single delivery transaction', function() {

    $response = $this->postJson('/temp_save', [
        "name" => "Randy Corpuz",
        "mobile" => "09174128392",
        "email" => "evilryok@gmail.com",
        "agent" => "Agent code",
        "shipping_type" => "delivery",
        "coupons" => "[]",
        "coupon_data" => "[]",
        "discount_amount" => 0,
        "order_amount" => 14800,
        "delivery_fee" => 650,
        "deposit" => "15450.00",
        "total_amount" => 15450,
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

    logger('delivery', ['response' => $response->json()]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);
});

it('creates multi-delivery transaction', function() {

    $deliveries = [
        [
            "orders" => [
                [
                    "product_id" => 257,
                    "paella" => false,
                    "is_free_product" => false,
                    "qty" => 1,
                    "product" => [
                        "id" => 257,
                        "order" => 3,
                        "category_id" => 1,
                        "name" => "Whole Lechon (Small)",
                        "slug" => "whole-lechon-small-2",
                        "price" => "14800.0000",
                        "size" => "Whole Lechon (Small)",
                        "paella_price" => "5300.0000",
                        "for_sale_web" => 1,
                        "for_sale_kiosk" => 0,
                        "uom" => '',
                        "size" => '',
                        "is_misc" => 0,
                        "production_item" => 1,
                        "free" => null,
                        "upsell" => null,
                        "sold_out" => 0
                    ],
                    "product_name" => "Whole Lechon (Small)"
                    
                ],
            ],
            "need_date" => now()->addDays(6)->format('Y-m-d'),
            "need_time" => "15:00",
            "address" => "BARANGAY 275, SAN NICOLAS, NCR - MANILA",
            "province" => "NCR - MANILA",
            "city" => "SAN NICOLAS",
            "location" => "BARANGAY 275",
            "name" => "Person 1",
            "phone" => "09174128392",
            "note" => "",
            "delivery_fee" => 650,
            "isEditingAddress" => false,
            "street" => "",
            "sms" => false,
            "cochinillo_warning" => false,
            "paella" => false,
            "isBaka" => false,
            "lechon_baka_service" => 0
        ],
        [
            "orders" => [
                [
                    "product_id" => 300,
                    "paella" => false,
                    "is_free_product" => false,
                    "qty" => 1,
                    "product" => [
                        "id" => 300,
                        "order" => 7,
                        "category_id" => 1,
                        "name" => "Whole Lechon - Jumbo",
                        "slug" => "whole-lechon-jumbo",
                        "price" => "30800.0000",
                        "size" => "Whole Lechon (Jumbo)",
                        "paella_price" => "0.0000",
                        "for_sale_web" => 1,
                        "uom" => '',
                        "size" => '',
                        "for_sale_kiosk" => 0,
                        "is_misc" => 0,
                        "production_item" => 1,
                        "free" => null,
                        "upsell" => null,
                        "sold_out" => 0
                    ],
                    "product_name" => "Whole Lechon - Jumbo"
                ]
            ],
            "need_date" => now()->addDays(6)->format('Y-m-d'),
            "need_time" => "15:00",
            "address" => "BARANGAY 288, BINONDO, NCR - MANILA",
            "province" => "NCR - MANILA",
            "city" => "BINONDO",
            "location" => "BARANGAY 288",
            "name" => "Person 2",
            "phone" => "09174128391",
            "note" => "",
            "delivery_fee" => 650,
            "isEditingAddress" => false,
            "street" => "",
            "sms" => false,
            "cochinillo_warning" => false,
            "paella" => false,
            "isBaka" => false,
            "lechon_baka_service" => 0
        ],
    ];

    $deliveries = json_encode($deliveries);

    $response = $this->postJson('/temp_save', [
        "name" =>"Randy Corpuz",
        "mobile" =>"09174128392",
        "email" =>"evilryok@gmail.com",
        "agent" =>"",
        "shipping_type" =>"delivery",
        "coupons" =>"[]",
        "coupon_data" =>"[]",
        "discount_amount" =>0,
        "order_amount" =>45600,
        "delivery_fee" =>1300,
        "deposit" =>"46900.00",
        "total_amount" =>46900,
        "isBaka" =>0,
        "lechon_baka_service" =>0,
        "delivery_address" =>"BARANGAY 275, SAN NICOLAS, NCR - MANILA",
        "province" =>"NCR - MANILA",
        "city" =>"SAN NICOLAS",
        "location" =>"BARANGAY 275",
        'deliveries' => $deliveries
    ]);

    logger('multi-deliverydelivery', ['response' => $response->json()]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);
});

it('should send sms and email', function () {

    $notificationService = Mockery::mock(NotificationService::class);

    $notificationService->shouldReceive('sendSms')->once();
    $notificationService->shouldReceive('sendEmail')->once();

    $sendNotification = app(SendNotification::class);

    $salesHeader = SalesHeader::factory()->create();
    $user = User::factory()->create();

    $sendNotification->process($notificationService, $salesHeader, $user, null);

    expect(true)->toBeTrue();
});