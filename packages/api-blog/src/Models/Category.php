<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    Const Category_STATUS = [
        'draft' => 'draft',
        'publish' => 'publish',
        'pending' => 'pending',
        'future' => 'future',
        'private' => 'private',
    ];

    Const Category_TYPE = [
        'article' => 'article',
        'news' => 'news',
        'page' => 'page',
    ];

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

    public static function listPagination(array $input) : Collection

    {
        return  self::select('id','name','title','slug','subject','pic_small','created_at','updated_at')
            ->where('status', self::Category_STATUS['publish'])
            ->where('category_type', $input['category_type'])
            ->skip($input['skip'])->take($input['take'])->get();
    }
}
