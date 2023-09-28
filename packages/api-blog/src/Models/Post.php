<?php

namespace Admin\ApiBolg\Models;

use Admin\ApiBolg\Traits\TranslatableModel;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Translatable\HasTranslations;
use Database\Factories\PostFactory;

use DateTimeInterface;
class Post extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    use TranslatableModel, HasTranslations {
        TranslatableModel::setTranslation insteadof HasTranslations;
    }

    public $translatable = [
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
        'issue_type',
        'pic_small',
        'pic_large',
    ];

    const issue_type = [
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
        'issue_type',
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

    public static function listPagination(array $input): LengthAwarePaginator
    {
        $local = $input['local'] ?? null;
        $tags = $input['tags'] ?? null;
        $issue_type = $input['issue_type'] ?? null;
        $list_trash = $input['list_trash'] ?? null;

        if ($tags) {
            return Tag::paginate(config('view.pagination'));
        }
        $query = self::select('id', 'name', 'title', 'slug', 'subject', 'issue_type', 'pic_small', 'menu_order', 'menu_status', 'created_at','priority', 'updated_at','category_id');
        $query->when($issue_type, function ($query, $issue_type) use ($local) {
            return $query->where("issue_type->$local", $issue_type);
        });
        $query->when($list_trash, function ($query) {
                return $query->onlyTrashed();
            });
        $query->orderBy('id', 'DESC')->with('category:id,name');
        // Return the paginated results using the config() helper function
        return $query->paginate(config('view.pagination'));

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
        static::deleting(function ($posts) {
            Comment::withTrashed()->where('post_id', $posts->id)->delete();
        });
        // before restore() method call this and restore all comments related to this post
        static::restoring(function ($posts) {
            Comment::withTrashed()->where('post_id', $posts->id)->restore();
        });
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y/m/d H:i:s');
    }
    // Override the newFactory method
    protected static function newFactory()
    {
        // Return an instance of your factory class with the correct namespace
        return PostFactory::new();
    }
}
