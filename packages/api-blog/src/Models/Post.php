<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'title',
        'status',
        'slug',
        'subject',
        'description',
        'meta_description',
        'meta_keywords',
        'meta_language',
        'tweet_text',
        'post_type',
        'menu_order',
        'pic_small',
        'pic_large',
        'priority',
        'comment_status',
        'menu_status',
        'visible_index_status',
        'category_id',
    ];
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->as('tags');
    }
}
