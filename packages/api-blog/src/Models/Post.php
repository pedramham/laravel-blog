<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    Const POST_STATUS = [
        'draft' => 'draft',
        'publish' => 'publish',
        'pending' => 'pending',
        'future' => 'future',
        'private' => 'private',
    ];

    Const POST_TYPE = [
        'article' => 'article',
        'news' => 'news',
        'page' => 'page',
    ];

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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->as('tags');
    }

    public static function listPagination(array $input) : Collection

    {
        return  self::select('id','name','title','slug','subject','pic_small','created_at','updated_at')
            ->where('status', self::POST_STATUS['publish'])
            ->where('post_type', $input['post_type'])
            ->skip($input['skip'])->take($input['take'])->get();
    }
}
