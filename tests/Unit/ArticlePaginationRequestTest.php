<?php

namespace Tests\Unit;

use App\Http\Controllers\FrontendController;
use App\Http\Requests\ArticlePaginationRequest;
use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class ArticlePaginationRequestTest extends TestCase
{
    public function test_invalid_pagination_is_rejected_before_querying(): void
    {
        $invalidValues = ['$', '', null, 0, -1, '1.5', ['2'], '999999999999999999999999'];

        foreach (['page', 'limit'] as $field) {
            foreach ($invalidValues as $value) {
                $request = $this->paginationRequest([$field => $value]);

                try {
                    $request->validateResolved();
                    $this->fail('Expected invalid '.$field.' to be rejected.');
                } catch (ValidationException $exception) {
                    $this->assertSame(422, $exception->status);
                    $this->assertArrayHasKey($field, $exception->errors());
                }
            }
        }

        $request = $this->paginationRequest(['limit' => 101]);
        $this->expectException(ValidationException::class);
        $request->validateResolved();
    }

    public function test_defaults_and_valid_numeric_inputs_are_accepted(): void
    {
        $validInputs = [
            [],
            ['page' => '2', 'limit' => '4'],
            ['page' => 1, 'limit' => 100],
        ];

        foreach ($validInputs as $input) {
            $request = $this->paginationRequest($input);
            $request->validateResolved();

            $this->assertSame($input, $request->validated());
            $this->assertSame((int) ($input['page'] ?? 1), (int) $request->input('page', 1));
            $this->assertSame((int) ($input['limit'] ?? 4), (int) $request->input('limit', 4));
        }
    }

    public function test_both_article_endpoints_require_validated_pagination(): void
    {
        foreach (['articleLoadMore', 'articleCategoryLoadMore'] as $methodName) {
            $method = new ReflectionMethod(FrontendController::class, $methodName);

            $this->assertSame(ArticlePaginationRequest::class, $method->getParameters()[0]->getType()->getName());
        }
    }

    private function paginationRequest(array $input): ArticlePaginationRequest
    {
        $container = new Container();
        $validator = new Factory(new Translator(new ArrayLoader(), 'en'), $container);
        $container->instance(ValidationFactory::class, $validator);

        $request = ArticlePaginationRequest::create('/articles/load-more', 'GET', $input);
        $request->headers->set('Accept', 'application/json');
        $request->setContainer($container);

        $urlGenerator = new UrlGenerator(new RouteCollection(), Request::create('/'));
        $request->setRedirector(new Redirector($urlGenerator));

        return $request;
    }
}
