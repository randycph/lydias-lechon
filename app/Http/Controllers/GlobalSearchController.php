<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'Article' => [
                'class' => \App\Models\Article::class,
                'fields' => ['name', 'contents', 'teaser'],
                'status' => 'Published',
            ],
        ];

        foreach ($models as $modelName => $settings) {
            $modelClass = $settings['class'];
            $fields = $settings['fields'];
            $status = $settings['status'];
        
            $query = $modelClass::query();
        
            $query->where('status', $status);
        
            $query->where(function($q) use ($fields, $searchTerm) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            });
        
            $modelResults = $query->get();
        
            if ($modelResults->isNotEmpty()) {
                $results[$modelName] = $modelResults;
            }
        }

        return response()->json($results);
    }
}
