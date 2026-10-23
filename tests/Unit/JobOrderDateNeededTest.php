<?php

namespace Tests\Unit;

use App\Http\Controllers\EcommerceControllers\JoborderController;
use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class JobOrderDateNeededTest extends TestCase
{
    private Container $previousContainer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->previousContainer = Container::getInstance();
        $container = new Container();
        Container::setInstance($container);

        $responses = $this->createMock(ResponseFactory::class);
        $responses->method('json')->willReturnCallback(function ($data) {
            return new JsonResponse($data);
        });
        $container->instance(ResponseFactory::class, $responses);
    }

    protected function tearDown(): void
    {
        Container::setInstance($this->previousContainer);

        parent::tearDown();
    }

    public function test_incomplete_and_invalid_dates_return_a_reminder(): void
    {
        $invalidDates = [
            null,
            '',
            '2026-10-23',
            '2026-10-23 - ',
            ' - 12:00',
            '2026-02-30 - 12:00',
            '2026-10-23 - 25:00',
            "2026-10-23 - 12:00\0",
            ['2026-10-23 - 12:00'],
        ];

        foreach ($invalidDates as $dateNeeded) {
            $data = $this->checkDateNeeded($dateNeeded, 0);

            $this->assertSame(1, $data['remark']);
            $this->assertStringContainsString('valid delivery date and time', $data['err']);
        }
    }

    public function test_valid_dates_preserve_operating_hours_for_all_product_types(): void
    {
        $futureDate = date('Y-m-d', strtotime('+4 days'));

        foreach ([0, 1, 2] as $productType) {
            foreach (['05:00', '12:00', '21:00'] as $deliveryTime) {
                $data = $this->checkDateNeeded($futureDate.' - '.$deliveryTime, $productType);

                $this->assertSame(0, $data['remark']);
                $this->assertSame('', $data['err']);
            }
        }

        foreach (['04:00', '22:00'] as $deliveryTime) {
            $data = $this->checkDateNeeded($futureDate.' - '.$deliveryTime, 0);

            $this->assertSame(1, $data['remark']);
            $this->assertStringContainsString('beyond our operation time', $data['err']);
        }
    }

    public function test_short_notice_preserves_each_product_reminder(): void
    {
        $recentDate = date('Y-m-d H:i', strtotime('-1 hour'));
        $dateNeeded = str_replace(' ', ' - ', $recentDate);

        foreach ([0 => 'less than 2 hours', 1 => 'less than 24 hours', 2 => 'less than 72 hours'] as $productType => $message) {
            $data = $this->checkDateNeeded($dateNeeded, $productType);

            $this->assertSame(1, $data['remark']);
            $this->assertStringContainsString($message, $data['err']);
        }
    }

    private function checkDateNeeded(mixed $dateNeeded, int $productType): array
    {
        // Bypass permission setup so these checks never access the database.
        $controller = (new ReflectionClass(JoborderController::class))->newInstanceWithoutConstructor();
        $request = new Request([
            'dateneeded' => $dateNeeded,
            'has_lechon' => $productType,
        ]);

        return $controller->check_dateneeded($request)->getData(true);
    }
}
