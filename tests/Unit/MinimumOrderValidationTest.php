<?php

namespace Tests\Unit;

use App\Services\SalesValidationService;
use PHPUnit\Framework\TestCase;

class MinimumOrderValidationTest extends TestCase
{
    public function test_minimum_depends_on_selected_shipping_method(): void
    {
        $service = new SalesValidationService();
        $settings = (object) ['minimum_order' => 3000, 'minimum_order_pickup' => 0, 'minimum_order_misc' => 0];
        $cart = collect([
            ['product_id' => 1, 'qty' => 1, 'product' => ['price' => 300, 'is_misc' => 0]],
        ]);

        $this->assertNull($service->minimumOrderMessage($cart, $settings, 'pickup'));
        $this->assertStringContainsString('₱3,000.00', $service->minimumOrderMessage($cart, $settings, 'delivery'));
        $this->assertStringContainsString('valid shipping method', $service->minimumOrderMessage($cart, $settings, 'invalid'));
    }

    public function test_subtotal_includes_paella_and_excludes_service_item(): void
    {
        $service = new SalesValidationService();
        $settings = (object) ['minimum_order' => 3000, 'minimum_order_pickup' => 0, 'minimum_order_misc' => 0];
        $cart = collect([
            ['product_id' => 1, 'qty' => 2, 'paella_price' => 100, 'product' => ['price' => 1400, 'paella_price' => 100]],
            ['product_id' => 270, 'qty' => 1, 'product' => ['price' => 3500]],
        ]);

        $this->assertNull($service->minimumOrderMessage($cart, $settings, 'delivery'));
        $item = $cart[0];
        $item['qty'] = 1;
        $cart[0] = $item;
        $this->assertStringContainsString('₱3,000.00', $service->minimumOrderMessage($cart, $settings, 'delivery'));
    }

    public function test_miscellaneous_minimum_is_enforced(): void
    {
        $service = new SalesValidationService();
        $settings = (object) ['minimum_order' => 0, 'minimum_order_pickup' => 0, 'minimum_order_misc' => 500];
        $cart = collect([
            ['product_id' => 1, 'qty' => 1, 'product' => ['price' => 300, 'is_misc' => 1]],
        ]);

        $this->assertStringContainsString('miscellaneous items', $service->minimumOrderMessage($cart, $settings, 'pickup'));
    }
}
