<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Article;
use App\Models\Concerns\LogsActivityDiff;

class ArticleCategory extends Model
{
    use SoftDeletes;
    use LogsActivityDiff;

    protected $table = 'article_categories';
    protected $fillable = ['name', 'slug', 'user_id', 'image'];
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
