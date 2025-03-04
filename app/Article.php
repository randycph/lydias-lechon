<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $table = 'articles';
    protected $fillable = ['date', 'teaser', 'is_featured', 'slug', 'name', 'contents', 'status', 'image_url', 'meta_title', 'meta_keyword', 'meta_description', 'user_id', 'category_id','external_link','thumbnail_url','is_blog'];

    public function tags()
    {
        return $this->hasMany(ArticleTag::class);
    }

    public function tags_string()
    {
        return $this->tags()->pluck('tag')->implode(',');
    }

//    public function getImageUrlAttribute($value)
//    {
//        if (empty($value)) {
//            return url('/').'/images/no-image-available.jpg';
//        }
//
//        return $value;
//    }
    public static function base_front_url()
    {
        return env('APP_URL')."/news/";
    }

    public static function totalArticles()
    {
        $total = Article::withTrashed()->get()->count();

        return $total;
    }

    public static function totalPublishedArticles()
    {
        $total = Article::where('status','Published')->count();

        return $total;
    }

    public static function totalDraftArticles()
    {
        $total = Article::where('status','Private')->count();

        return $total;
    }

    public static function totalDeletedArticles()
    {
        $withTrashed = Article::withTrashed()->get()->count();
        $total = $withTrashed - Article::count();

        return $total;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\ArticleCategory')->withDefault([
            'name' => 'Uncategorized',
            'id' => '0',
        ]);
    }

    public function get_url()
    {
        return env('APP_URL')."/news/".$this->slug;
    }

    public function get_created_at_date_only()
    {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }

    public function get_image_url_storage_path()
    {
        $delimiter = 'storage/';
        if (strpos($this->image_url, $delimiter) !== false) {
            $paths = explode($delimiter, $this->image_url);
            return $paths[1];
        }

        return '';
    }

    public function get_image_file_name()
    {
        $path = explode('/', $this->image_url);
        $nameIndex = count($path) - 1;
        if ($nameIndex < 0)
            return '';

        return $path[$nameIndex];
    }

    public function get_tags($id)
    {
        $tag = ArticleTag::where('article_id', $id)->value('tag');

        return $tag;
    }



    public function getTeaserlinkAttribute(){
        $string = $this->teaser;
        $len = 150;
        if (strlen($string) > $len) {

            // truncate string
            $stringCut = substr($string, 0, $len);
            $endPoint = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            // if($this->is_blog==1){
            //     $string .= '... <a href="'.$this->external_link.'" target="_blank">Read More</a>';
            // }
            // else{
            //     $string .= '... <a href="'.route('news.front.show',$this->slug).'">Read More</a>';
            // }
        }

        if($this->is_blog==1){
            $string .= '... <a href="'.$this->external_link.'" target="_blank">Read More</a>';
        }
        else{
            $string .= '... <a href="'.route('news.front.show',$this->slug).'">Read More</a>';
        }

        return $string;
    }
}
