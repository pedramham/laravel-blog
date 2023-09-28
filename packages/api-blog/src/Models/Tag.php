<?php

namespace Admin\ApiBolg\Models;

use Admin\ApiBolg\Traits\TranslatableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasFactory, SoftDeletes,HasTranslations;

    use TranslatableModel, HasTranslations {
        TranslatableModel::setTranslation insteadof HasTranslations;
    }

    public $translatable = [
        'name',
        'description',
    ];

    protected $fillable = ['name', 'description'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class,'video');
    }
}
