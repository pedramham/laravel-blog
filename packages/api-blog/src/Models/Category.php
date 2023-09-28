<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;
use Spatie\Translatable\HasTranslations;
use Admin\ApiBolg\Traits\TranslatableModel;
use Database\Factories\CategoryFactory;

/**
 * @mixin Builder
 */
class Category extends Model
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
        'issue_type',
        'menu_order',
        'priority',
        'menu_status',
        'visible_index_status',
        'pic_small',
        'pic_large',
        'parent_id',
    ];

    public static function listPagination(array $input): LengthAwarePaginator

    {
        // Extract the query parameters from the input array
        $local = $input['local'];
        $status = $input['status'] ?? null;
        $issue_type = $input['issue_type'] ?? null;
        $parent_id = $input['parent_id'] ?? null;
        $list_trash = $input['list_trash'] ?? null;

        // Select the columns to display from the issues table
        $query = self::select('id', 'name', 'title', 'slug', 'subject','menu_order','priority','menu_status', 'pic_small', 'created_at', 'updated_at','issue_type');

        // Filter the issues by the name column based on the input locale
        $query->whereNotNull("name->" . $local);

        // Conditionally apply filters to the query using the when() method
        $query->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
            ->when($issue_type, function ($query, $issue_type) {
                return $query->where('issue_type', $issue_type);
            })
            ->when($parent_id, function ($query, $parent_id) {
                return $query->where('parent_id', $parent_id);
            })
            ->when($list_trash, function ($query) {
                return $query->onlyTrashed();
            });
        $query->orderBy('id', 'DESC');
        // Return the paginated results using the config() helper function
        return $query->paginate(config('view.pagination'));
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    // Override the newFactory method
    protected static function newFactory()
    {
        // Return an instance of your factory class with the correct namespace
        return CategoryFactory::new();
    }

}
