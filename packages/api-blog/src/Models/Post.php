<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    const POST_STATUS = [
        'draft' => 'draft',
        'publish' => 'publish',
        'pending' => 'pending',
        'future' => 'future',
        'private' => 'private',
    ];

    const POST_TYPE = [
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function listPagination(array $input): Collection
    {
        return self::select('id', 'name', 'title', 'slug', 'subject', 'pic_small', 'created_at', 'updated_at')
            ->where('status', self::POST_STATUS['publish'])
            ->where('post_type', $input['post_type'])
            ->skip($input['skip'])->take($input['take'])->get();
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Override parent boot and Call deleting event
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // before delete() method call this and soft delete all comments related to this post
        static::deleting(function($posts) {
            Comment::withTrashed()->where('post_id', $posts->id)->delete();
        });
        // before restore() method call this and restore all comments related to this post
        static::restoring(function($posts) {
            Comment::withTrashed()->where('post_id', $posts->id)->restore();
        });
    }
}
