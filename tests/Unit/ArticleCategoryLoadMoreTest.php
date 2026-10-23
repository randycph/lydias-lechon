<?php

namespace Tests\Unit;

use App\Http\Controllers\FrontendController;
use App\Http\Requests\ArticlePaginationRequest;
use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\TestCase;

class ArticleCategoryLoadMoreTest extends TestCase
{
    public function test_category_lookup_handles_missing_deleted_and_valid_categories(): void
    {
        $previousContainer = Container::getInstance();
        $previousResolver = Model::getConnectionResolver();
        $container = new Container();
        Container::setInstance($container);

        try {
            // All database queries in this test use a disposable in-memory database.
            $database = new Manager($container);
            $database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
            Model::setConnectionResolver($database->getDatabaseManager());
            $connection = $database->getConnection();
            $connection->statement('CREATE TABLE article_categories (id INTEGER PRIMARY KEY, slug TEXT, deleted_at TEXT)');
            $connection->statement('CREATE TABLE articles (id INTEGER PRIMARY KEY, category_id INTEGER, is_blog INTEGER, status TEXT, created_at TEXT, deleted_at TEXT)');
            $connection->table('article_categories')->insert([
                ['id' => 1, 'slug' => 'news', 'deleted_at' => null],
                ['id' => 2, 'slug' => 'archived', 'deleted_at' => '2026-10-01'],
            ]);
            $connection->table('articles')->insert([
                'id' => 1,
                'category_id' => 1,
                'is_blog' => 1,
                'status' => 'Published',
                'created_at' => '2026-10-01',
                'deleted_at' => null,
            ]);

            $controller = new FrontendController();

            foreach ([[], ['category' => ''], ['category' => ['news']]] as $input) {
                try {
                    $controller->articleCategoryLoadMore($this->categoryRequest($input));
                    $this->fail('Expected invalid category input to be rejected.');
                } catch (ValidationException $exception) {
                    $this->assertArrayHasKey('category', $exception->errors());
                }
            }

            foreach (['missing', 'archived'] as $slug) {
                try {
                    $controller->articleCategoryLoadMore($this->categoryRequest(['category' => $slug]));
                    $this->fail('Expected an unavailable category to produce a not-found exception.');
                } catch (ModelNotFoundException $exception) {
                    $this->assertSame(\App\Models\ArticleCategory::class, $exception->getModel());
                }
            }

            $view = $this->createMock(View::class);
            $view->method('render')->willReturn('<div>Article</div>');
            $views = $this->createMock(ViewFactory::class);
            $views->expects($this->once())->method('make')->with(
                'v2.partials.articles',
                $this->callback(function ($data) {
                    $this->assertCount(1, $data['articles']);
                    $this->assertSame('news', $data['articles']->first()->category->slug);

                    return true;
                }),
                []
            )->willReturn($view);
            $container->instance(ViewFactory::class, $views);

            $responses = $this->createMock(ResponseFactory::class);
            $responses->method('json')->willReturnCallback(function ($data) {
                return new JsonResponse($data);
            });
            $container->instance(ResponseFactory::class, $responses);

            $response = $controller->articleCategoryLoadMore($this->categoryRequest(['category' => 'news']));

            $this->assertSame(['html' => '<div>Article</div>', 'hasMore' => false], $response->getData(true));
        } finally {
            if ($previousResolver === null) {
                Model::unsetConnectionResolver();
            } else {
                Model::setConnectionResolver($previousResolver);
            }
            Container::setInstance($previousContainer);
        }
    }

    private function categoryRequest(array $input): ArticlePaginationRequest
    {
        return new class($input) extends ArticlePaginationRequest {
            public function validate(array $rules, array $messages = []): array
            {
                $factory = new Factory(new Translator(new ArrayLoader(), 'en'));

                return $factory->make($this->all(), $rules, $messages)->validate();
            }
        };
    }
}
