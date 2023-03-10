<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = [
        'deleted_at',
        'updated_at',
    ];

    protected $table = 'comment';

    protected $fillable = [
        'name',
        'email',
        'post_id',
        'comments',
        'status',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public static function listPagination(array $input): Collection
    {
        return self::all()->skip($input['skip'])->take($input['take']);
    }

}
