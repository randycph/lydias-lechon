<?php

namespace App\Http\Controllers;

use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GlobalSearchController extends Controller
{
    public function search(Request $request) 
    {
        $searchTerm = $request->input('searchTerm');

        $validator = \Validator::make($request->all(), [
            'searchTerm' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        $results = [];

        $models = [
            'Product' => [
                'class' => \App\Models\Product::class,
                'fields' => ['name', 'short_description', 'description'],
                'status' => 'PUBLISHED',
            ],
            'ProductCategory' => [
                'class' => \App\Models\ProductCategory::class,
                'fields' => ['name', 'description'],
                'status' => 'PUBLISHED',
            ],
            'Article' => [
                'class' => \App\Models\Article::class,
                'fields' => ['name', 'contents', 'teaser'],
                'status' => 'Published',
            ],
            'Store' => [
                'class' => \App\EcommerceModel\Branch::class,
                'fields' => ['name', 'address', 'contact_nos','hotline'],
            ],
        ];

        foreach ($models as $modelName => $settings) {
            $modelClass = $settings['class'];
            $fields = $settings['fields'];
            $status = $settings['status'] ?? null;
        
            $query = $modelClass::query();
            
            if ($status !== null) {
                $query->where('status', $status);
            }
        
            $query->where(function($q) use ($fields, $searchTerm) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            });
        
            $modelResults = $query->get();

            if ($modelName === 'Article' && $modelResults->isNotEmpty()) {
                $modelResults = $modelResults->map(function ($article) {
                    $category = ArticleCategory::find($article->category_id);
                    $categorySlug = Str::slug($category->name ?? 'uncategorized');
                    
                    $article->article_url = route("article", [
                        'category' => $categorySlug,
                        'slug' => $article->slug,
                    ]);
                    return $article;
                });
            }

            if ($modelName === 'Product') {
                $query->with(['photos']);
            }

            if ($modelName === 'Product' && $modelResults->isNotEmpty()) {
                $modelResults = $modelResults->map(function ($product) {
                    $slug = Str::slug($product->slug ?? $product->name, '-');
                    $product->product_url = 'menu/' . '?product=' . urlencode($slug);
                    $product->photo_url = $product->photos->last()?->path 
                        ? asset('storage/products/' . $product->photos->last()->path) 
                        : null;
                    return $product;
                });
            }

            if ($modelName === 'ProductCategory' && $modelResults->isNotEmpty()) {
                $modelResults = $modelResults->map(function ($category) {
                    $slug = Str::slug($category->slug ?? $category->name, '-');
                    $category->product_category_url = 'menu/' . '?s=' . urlencode($slug);
                    $category->photo_url = $category->image ? asset('images/category/' . $category->image)  : null;
                    return $category;
                });
            }

            if ($modelName === 'Store' && $modelResults->isNotEmpty()) {
                $modelResults = $modelResults->map(function ($branch) {
                    $slug = Str::slug($branch->name, '-');
                    $branch->store_url = route('page', ['slug' => 'stores']) . '?s=' . urlencode($slug);
                    $branch->photo_url = null;
                    return $branch;
                });
            }
        
            if ($modelResults->isNotEmpty()) {
                $results[$modelName] = $modelResults;
            }
        }

        return response()->json($results);
    }
}
