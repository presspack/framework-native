<?php

namespace Presspack\Framework\Support;

use Illuminate\Database\Eloquent\Model;
use Presspack\Framework\Post;

class Translation extends Model
{
    protected $table = 'icl_translations';
    protected $primaryKey = 'translation_id';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
