<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class LatestHomepageShortcode extends Controller
{
    public function render($attributes = [])
    {
		$featuredNews = Article::where('is_featured', 1)->whereStatus('Published')->orderBy('date', 'desc')->get();

        if ($featuredNews->count() == 0) {
            return '';
        }

        return view('shortcodes.latest_homepage', compact('featuredNews'))->render();
    }
}
