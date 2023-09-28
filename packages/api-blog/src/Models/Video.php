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
class Video extends Model
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
        'thumbnail',
        'video_url',
        'video_file',
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
        'duration',
        'thumbnail',
        'video_url',
        'video_file',
        'video_file_type',
        'video_number',
        'priority',
        'video_course_id',

    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class,'video_tag')->as('tags');
    }

    public function VideoCourse(): BelongsTo
    {
        return $this->belongsTo(VideoCourse::class);
    }

    public static function listPagination(array $input): LengthAwarePaginator
    {
        $local = $input['local'] ?? null;
        $tags = $input['tags'] ?? null;
        $list_trash = $input['list_trash'] ?? null;

        if ($tags) {
            return Tag::paginate(config('view.pagination'));
        }

        $query = Video::query();
        $query->select('id', 'name', 'title', 'slug', 'subject', 'thumbnail', 'video_number','duration','priority', 'created_at','video_course_id');

        $query->when($list_trash, function ($query) {
                return $query->onlyTrashed();
            });
        $query->orderBy('id', 'DESC')->with('videoCourse:id,name');
        // Return the paginated results using the config() helper function
        return $query->paginate(config('view.pagination'));

    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y/m/d H:i:s');
    }
}
