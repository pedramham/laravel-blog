<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'title',
        'slug',
        'status',
        'subject',
        'description',
        'meta_description',
        'meta_keywords',
        'meta_language',
        'tweet_text',
        'category_type',
        'menu_order',
        'priority',
        'menu_status',
        'visible_index_status',
        'pic_small',
        'pic_large',
        'parent_id',
    ];
}
