<?php

namespace App\Models;

use App\Models\Concerns\LogsActivityDiff;
use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    use LogsActivityDiff;
    
    public $table = 'article_tags';
    protected $fillable = [ 'article_id', 'tag', 'created_by' ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
