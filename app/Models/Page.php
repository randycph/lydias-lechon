<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use SoftDeletes;

    protected $table = 'pages';
    protected $fillable = ['parent_page_id', 'album_id', 'slug', 'name', 'label', 'contents', 'status', 'page_type', 'image_url', 'meta_title', 'meta_keyword', 'meta_description', 'user_id', 'template','deleted_at'];

    // public function album()
    // {
    //     return $this->belongsTo(Album::class);
    // }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menus_has_pages');
    }

    public function parent_page()
    {
        return $this->hasOne(Page::class, 'id', 'parent_page_id');
    }

    public function has_parent_page()
    {
        return $this->parent_page && $this->parent_page->count() > 0;
    }

    public function sub_pages()
    {
        return $this->hasMany(Page::class, 'parent_page_id');
    }

    public function has_sub_pages()
    {
        return $this->sub_pages && $this->sub_pages->count() > 0;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function album()
    {
        return $this->belongsTo('App\Models\Album')->withDefault([
            'name' => 'No album',
            'id' => '0',
        ]);
    }

    public function has_slider()
    {
        return empty($this->image_url);
    }

    public function is_published()
    {
        return strtolower($this->status) == 'published';
    }

    public function is_editable()
    {
        return $this->page_type != 'uneditable';
    }

    public function is_not_editable()
    {
        return $this->page_type == 'uneditable';
    }

    public function get_url()
    {
//        if($this->parent_page) {
//            $url = $this->parent_page->slug.'/'.$this->slug;
//            $parentPage = $this->parent_page;
//            while($parentPage->parent_page_id != 0) {
//                $parentPage = $parentPage->parent_page;
//                $url = $parentPage->slug.'/'.$url;
//            }
//
//            return env('APP_URL')."/".$url;
//        }

        return env('APP_URL')."/".$this->slug;
    }

    public static function totalPages()
    {
        $total = Page::withTrashed()->get()->count();

        return $total;
    }

    public static function totalPublicPages()
    {
        $total = Page::where('status','PUBLISHED')->count();

        return $total;
    }

    public static function totalPrivatePages()
    {
        $total = Page::where('status','PRIVATE')->count();

        return $total;
    }

    public static function totalDeletePages()
    {
        $withTrashed = Page::withTrashed()->get()->count();
        $total = $withTrashed - Page::count();

        return $total;
    }

    public static function convert_to_slug($url, $parentPage = 0){
        $url = str_slug($url, '-');

        $parentPage = Page::find($parentPage);
        if($parentPage) {
            $url = $parentPage->slug.'/'.$url;
        }

        if(self::check_if_slug_exists($url)){
            $url=$url.'-2';
            return self::convert_to_slug($url);
        }
        else{
            return $url;
        }
    }

    public static function check_if_slug_exists($slug){

        if (Page::where('slug', '=', $slug)->exists()) {
            return true;
        }
        elseif (Article::where('slug', '=', $slug)->exists()) {
            return true;
        }
        elseif (ArticleCategory::where('slug', '=', $slug)->exists()) {
            return true;
        }
        elseif (ProductCategory::where('slug', '=', $slug)->exists()) {
            return true;
        }
        elseif (Product::where('slug', '=', $slug)->exists()) {
            return true;
        }
        else{
            return false;
        }
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
}
