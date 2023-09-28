<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
class VideoCourse extends Model
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
        'menu_order',
        'priority',
        'menu_status',
        'visible_index_status',
        'pic_small',
        'pic_large',
        'price',
        'is_free',
        'sell_count',
        'video_category_id',
    ];

    public static function listPagination(array $input): LengthAwarePaginator

    {
        // Extract the query parameters from the input array
        $local = $input['local'] ?? "en";
        $status = $input['status'] ?? null;
        $list_trash = $input['list_trash'] ?? null;

        // Select the columns to display from the issues table
        $query = self::select('id', 'name', 'title', 'slug','price','is_free','sell_count', 'subject', 'created_at', 'updated_at','video_category_id');

        // Filter the issues by the name column based on the input locale
        $query->whereNotNull("name->" . $local);

        // Conditionally apply filters to the query using the when() method
        $query->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
            ->when($list_trash, function ($query) {
                return $query->onlyTrashed();
            });

        $query->orderBy('id', 'DESC')->with('videoCategory:id,name');
        // Return the paginated results using the config() helper function
        return $query->paginate(config('view.pagination'));
    }

    public function videoCategory(): BelongsTo
    {
        return $this->belongsTo(VideoCategory::class);
    }

}
