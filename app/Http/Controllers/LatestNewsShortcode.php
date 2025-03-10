<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class LatestNewsShortcode extends Controller
{
    public function render($attributes = [])
    {
        $limit = $attributes['limit'] ?? 5; // Default limit: 5

		$latest_news = Article::whereStatus('Published')->orderBy('date', 'desc')->take($limit)->get();

        return view('shortcodes.latest_news', compact('latest_news'))->render();
    }
}
