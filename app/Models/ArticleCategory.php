<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Article;

class ArticleCategory extends Model
{
    use SoftDeletes;

    protected $table = 'article_categories';
    protected $fillable = ['name', 'slug', 'user_id'];
    public $timestamps = true;

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id', 'id');
    }

    public function get_total_articles()
    {
        return $this->articles->count();
    }
}
