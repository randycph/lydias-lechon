<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL as LaravelURL;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap index + partitions (static + products).';

    public function handle(): int
    {
        $this->info('Generating single sitemap...');

        // 0) Validate APP_URL
        $baseUrl = rtrim(config('app.url'), '/');
        if (!$baseUrl) {
            $this->error('APP_URL is not set. Please set APP_URL in your .env.');
            return self::FAILURE;
        }

        // 1) Ensure /public is writable
        if (!File::isDirectory(public_path())) {
            File::makeDirectory(public_path(), 0755, true);
        }

        // 2) Create a single sitemap
        $sitemapPath = public_path('sitemap.xml');
        $sitemap = Sitemap::create();

        // 3) Static pages 
        $staticPages = [
            ['path' => '/', 'priority' => 1.0, 'freq' => Url::CHANGE_FREQUENCY_DAILY],
        ];

        foreach ($staticPages as $page) {
            $sitemap->add(
                Url::create($baseUrl . $page['path'])
                    ->setPriority($page['priority'])
                    ->setChangeFrequency($page['freq'])
            );
        }

        // 4) Dynamic urls, like products, articles, categories, pages etc.
        $menuBase = rtrim(config('app.url'), '/');

        $categories = ProductCategory::with(['products' => function ($query) {
                $query->where('status', 'PUBLISHED');
            }])
            ->where('status', 'PUBLISHED')
            ->orderBy('id')
            ->get();

        foreach ($categories as $category) {
            foreach ($category->products as $product) {
                $slug = (string) ($product->slug ?? $product->id);
                $productUrl = $menuBase . '/menu?product=' . rawurlencode($slug);

                $tag = Url::create($productUrl)
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY);

                if (!empty($product->updated_at)) {
                    $tag->setLastModificationDate($product->updated_at);
                }

                $sitemap->add($tag);
            }
        }

        $articles = Article::with('category')
                    ->where('is_blog', 1)
                    ->where('status', 'Published')
                    ->where('category_id', '>', 0)
                    ->latest()
                    ->get();

        foreach ($articles as $article) {
            $categorySlug = $article->category ? ($article->category->slug ?? 'uncategorized') : 'uncategorized';
            $articleUrl = route("article", [
                'category' => $categorySlug,
                'slug' => $article->slug,
            ]);

            $tag = Url::create($articleUrl)
                ->setPriority(0.5)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);

            if (!empty($article->updated_at)) {
                $tag->setLastModificationDate($article->updated_at);
            }

            $sitemap->add($tag);
        }

        $articleCategories = ArticleCategory::get();

        foreach ($articleCategories as $category) {
            $categorySlug = $category->slug ?? 'uncategorized';
            $categoryUrl = route("blogs-category", ['category' => $categorySlug]);

            $tag = Url::create($categoryUrl)
                ->setPriority(0.4)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);

            if (!empty($category->updated_at)) {
                $tag->setLastModificationDate($category->updated_at);
            }

            $sitemap->add($tag);
        }

        $pages = Page::where('status', 'PUBLISHED')->where('page_type', 'standard')->where('is_page', 1)->get();

        foreach ($pages as $page) {
            $pageUrl = LaravelURL::to(($page->slug ?? $page->id));

            $tag = Url::create($pageUrl)
                ->setPriority(0.3)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);

            if (!empty($page->updated_at)) {
                $tag->setLastModificationDate($page->updated_at);
            }

            $sitemap->add($tag);
        }

        // 5) Write the single sitemap
        $sitemap->writeToFile($sitemapPath);
        $this->info('✓ Wrote sitemap.xml (single file with static + products)');

        $this->info('All done!');
        return self::SUCCESS;
    }
}
