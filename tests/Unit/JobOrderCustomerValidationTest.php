<?php

namespace Tests\Unit;

use App\Http\Controllers\EcommerceControllers\JoborderController;
use Illuminate\Http\Request;
use Illuminate\Container\Container;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\PresenceVerifierInterface;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class JobOrderCustomerValidationTest extends TestCase
{
    public function test_valid_new_and_existing_customers_pass_customer_validation(): void
    {
        $previousContainer = Container::getInstance();
        $container = new Container();
        Container::setInstance($container);

        try {
            $cases = [
                ['customer_type' => 'cs-new'],
                ['customer_type' => 'cs-new', 'customer_id' => 'stale-input'],
                ['customer_type' => 'cs-exist', 'customer_id' => '123'],
            ];

            foreach ($cases as $input) {
                $session = new Store('test', new ArraySessionHandler(120));
                $redirector = new Redirector(new UrlGenerator(new RouteCollection(), Request::create('/')));
                $redirector->setSession($session);
                $container->instance('redirect', $redirector);

                $presenceVerifier = $this->createMock(PresenceVerifierInterface::class);
                $presenceVerifier->method('getCount')->willReturn(1);
                $factory = new Factory(new Translator(new ArrayLoader(), 'en'));
                $factory->setPresenceVerifier($presenceVerifier);

                $request = $this->validationRequest($input, $factory);
                $controller = (new ReflectionClass(JoborderController::class))->newInstanceWithoutConstructor();
                $response = $controller->store($request);

                // Missing payments stop this valid selection before any inserts.
                $this->assertSame(302, $response->getStatusCode());
                $this->assertTrue($session->get('errors')->has('payments'));
                $this->assertFalse($session->get('errors')->has('customer_id'));
            }
        } finally {
            Container::setInstance($previousContainer);
        }
    }

    public function test_invalid_customer_selection_is_rejected_before_saving(): void
    {
        $cases = [
            [[], 'customer_type'],
            [['customer_type' => 'invalid'], 'customer_type'],
            [['customer_type' => 'cs-exist'], 'customer_id'],
            [['customer_type' => 'cs-exist', 'customer_id' => null], 'customer_id'],
            [['customer_type' => 'cs-exist', 'customer_id' => 'invalid'], 'customer_id'],
            [['customer_type' => 'cs-exist', 'customer_id' => 123], 'customer_id'],
        ];

        $presenceVerifier = $this->createMock(PresenceVerifierInterface::class);
        $presenceVerifier->method('getCount')->willReturn(0);

        $factory = new Factory(new Translator(new ArrayLoader(), 'en'));
        $factory->setPresenceVerifier($presenceVerifier);

        // Exercise the controller without permission setup or database access.
        $controller = (new ReflectionClass(JoborderController::class))->newInstanceWithoutConstructor();

        foreach ($cases as [$input, $expectedField]) {
            $request = $this->validationRequest($input, $factory);

            try {
                $controller->store($request);
                $this->fail('Expected customer validation to stop this request.');
            } catch (ValidationException $exception) {
                $this->assertSame(422, $exception->status);
                $this->assertArrayHasKey($expectedField, $exception->errors());
            }
        }
    }

    private function validationRequest(array $input, Factory $factory): Request
    {
        $request = new class($input) extends Request {
            public Factory $validationFactory;

            public function validate(array $rules, array $messages = []): array
            {
                return $this->validationFactory->make($this->all(), $rules, $messages)->validate();
            }
        };
        $request->validationFactory = $factory;

        return $request;
    }
}
