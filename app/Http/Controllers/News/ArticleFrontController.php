<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Helpers\Webfocus\Setting;
use App\Http\Requests\Front\ShareEmailRequest;
use App\Mail\ShareNewsMail;
use App\Models\Page;
use App\Models\User;
use App\Models\Menu;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ArticlePost;

class ArticleFrontController extends Controller
{

    //Shortcode::enable();

    public function view($slug)
    {
        if(Auth::guest()) {
            $article = Article::where('slug',$slug)->whereStatus('Published')->first();
        } else {
            $article = Article::where('slug',$slug)->first();
        }

        if (!$article) {
            abort(404);
        }

        $breadcrumb = $this->breadcrumb($article);

        return view('theme.'.config('app.frontend_template').'.main',compact('page','breadcrumb'));

    }

    public function news_list(Request $request)
    {
        $pageLimit = 6;

        $articlesQuery = Article::query()->whereStatus('Published');

        // Search / filter
        if ($request->filled('type') && $request->filled('criteria')) {

            $type = $request->input('type');
            $criteria = $request->input('criteria');

            if ($type === 'searchbox') {
                $articlesQuery->where(function ($query) use ($criteria) {
                    $query->where('name', 'like', '%' . $criteria . '%')
                        ->orWhere('contents', 'like', '%' . $criteria . '%');
                });
            }
            elseif ($type === 'year') {
                $articlesQuery->whereYear('date', '=', $criteria);
            }
            elseif ($type === 'month') {
                $criterias = explode('-', $criteria);
                $year  = $criterias[0] ?? null;
                $month = $criterias[1] ?? null;

                if ($year && $month) {
                    $articlesQuery->whereYear('date', '=', $year)
                                ->whereMonth('date', '=', $month);
                }
            }
            elseif ($type === 'category') {
                if ((int) $criteria === 0) {
                    $articlesQuery->where(function ($query) {
                        $query->whereNull('category_id')
                            ->orWhere('category_id', '=', 0);
                    });
                } else {
                    $articlesQuery->where('category_id', '=', $criteria);
                }
            }
        }

        $articles = $articlesQuery
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($pageLimit)
            ->withQueryString();

        $dates = $this->dates();
        $categories = $this->categories();
        $breadcrumb = $this->breadcrumb();

        $page = Page::where('slug', 'news')->first();
        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();

        return view(
            'theme.' . config('app.frontend_template') . '.pages.news-list',
            compact('page', 'footer', 'articles', 'breadcrumb', 'dates', 'categories')
        );
    }

    public function dates($conditions=null){

        if($conditions){

        }
        else{
            $years = DB::select('SELECT year(date) as yr,count(id) as total_articles FROM `articles`  where deleted_at is null and status="Published" GROUP by year(date) ORDER BY year(date)');

            $data = '<ul>';

            foreach($years as $year){
                $data .= '<li><a href="'.route('news.front.index').'?type=year&criteria='.$year->yr.'">'.$year->yr.'  <span class="badge badge-info badge-pill float-right">'.$year->total_articles.'</span></a></li><ul>';

                $months = DB::select('SELECT year(date) as yr,month(date) as mo,count(id) as total_articles FROM `articles` WHERE year(date)="'.$year->yr.'" and deleted_at is null and status="Published" GROUP by year(date),month(date) ORDER BY month(date)');

                foreach($months as $month){
                    $data .= '<li><a href="'.route('news.front.index').'?type=month&criteria='.$year->yr.'-'.$month->mo.'">'.date("F", mktime(0, 0, 0, $month->mo, 1)).'  <span class="badge badge-info badge-pill float-right">'.$month->total_articles.'</span></a></li>';
                }

                $data .= '</ul></li>';
            }

            $data .= '</ul>';
        }

        return $data;

    }

    public function categories($conditions=null){

        if($conditions){

        }
        else{
            $categories = DB::select('SELECT ifnull(c.name, "Uncategorized") as cat, ifnull(c.id,0) as cid,count(ifnull(c.id,0)) as total_articles FROM `articles` a left join article_categories c on c.id=a.category_id where a.deleted_at is null and status="Published" GROUP BY c.name,c.id ORDER BY c.name');

            $data = '<ul class="list-group">';

            foreach($categories as $category){

                $data .= '<li><a href="'.route('news.front.index').'?type=category&criteria='.$category->cid.'">'.$category->cat.' <span class="badge badge-info badge-pill float-right">'.$category->total_articles.'</span></a><li>';

            }

            $data .= '</ul>';
        }

        return $data;

    }

    public function breadcrumb($article = null){

        $crumbs = ['home' => '/home'];
        $crumbs['News'] = route('news.front.index');

        if($article) {
            $article = Article::whereId($article)->first();
            $crumbs[$article->name] = route('news.front.show',$article->slug);
        }

        return $crumbs;

    }

    public function filter(Request $request){

        $conditions['type'] = $request->type;
        $conditions['criteria'] = $request->criteria;

        return $this->news_list($conditions);

    }

    public function news_view($slug){

        if(auth()->guest()) {
            $news = Article::where('slug','=',$slug)->whereStatus('Published')->first();
        } else {
            $news = Article::where('slug','=',$slug)->first();
        }

        if (!$news) {
            abort(404);
        }


        $breadcrumb = $this->breadcrumb($news->id);

        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();
        $page = $news;
        return view('theme.'.config('app.frontend_template').'.pages.news',compact('footer', 'news','breadcrumb', 'page'));

    }

    public function news_print($slug){

        $news = Article::where('slug',$slug)->whereStatus('Published')->first();

        if (!$news) {
            abort(404);
        }

        return view('theme.'.config('app.frontend_template').'.pages.news-print',compact('news'));

    }

    public function news_share($slug) {
        $news = Article::where('slug', $slug)->whereStatus('Published')->first();

        if (!$news) {
            return ['status' => 'failed'];
        }

        Mail::to(request()->email_to)->send(new ShareNewsMail(Setting::info(), $news, request()->email_from, request()->sender_name, request()->name));

        if (Mail::failures()) {
            return response()->json(['status' => 'failed', 404]);
        }

        return ['status' => 'success'];
    }

}
