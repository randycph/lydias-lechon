<?php
namespace App\Shortcodes;

use App\Models\Article;
use Illuminate\Support\Facades\URL;

class ArticlesShortcodes {

	public function latest($shortcode, $content, $compiler, $name, $viewData)
	{
		$limit = $shortcode->limit;
		$latest_news = Article::whereStatus('Published')->orderBy('date', 'desc')->take($limit)->get();

		$data = '
                    <h5 class="blog-sidebar-title uppercase">Recent Posts</h5>
                    <ul class="media-list main-list">';

        foreach($latest_news as $latest){
            $thumb = $latest->thumbnail_url;
            if(empty($latest->thumbnail_url)){
                $thumb = URL::to('/').'/storage/products/0/no_image_available.png';
            }
            $url = 'href="'.$latest->slug.'"';
            if($latest->is_blog == 1){
                $url = 'href="'.$latest->external_link.'" target="_blank"';
            }
        	$data.='
                    <li class="media">
                        <a class="pull-left" '.$url.'>
                            <img class="media-object" style="height:50px;" src="'.$thumb.'" alt="...">
                        </a>
                        <div class="media-body">
                            <a '.$url.'><h4 class="media-heading">'.$latest->name.'</h4></a>
                            <p class="by-author">'.$latest->created_at->diffForHumans().'</p>
                        </div>
                    </li>
				
        	';
        }


		return sprintf(''.$data.'</ul>', $shortcode->class, $content);
	}

    public function latest_homepage($shortcode, $content, $compiler, $name, $viewData)
    {
        $featuredNews = Article::where('is_featured', 1)->whereStatus('Published')->orderBy('date', 'desc')->get();

        if ($featuredNews->count() == 0) {
            return '';
        }

        return sprintf(view('theme.'.env('FRONTEND_TEMPLATE').'.pages.shortcodes.home-news',compact('featuredNews')), $shortcode->class, $content);
    }
}

?>
