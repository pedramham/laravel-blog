<?php

namespace Admin\ApiBolg\Models;

use Admin\ApiBolg\Traits\TranslatableModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'setting';

    use TranslatableModel, HasTranslations {
        TranslatableModel::setTranslation insteadof HasTranslations;
    }

    public $translatable = [
        'app_name',
        'app_favicon',
        'app_description',
        'app_keywords',
        'app_logo',
        'app_title',
        'app_short_description',
        'address',
        'phone',
        'email',
        'mobile',
        'fax',
        'telegram',
        'whatsapp',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'linkedin',
        'pinterest',
        'github',
    ];

    protected $fillable = [
        'app_name',
        'app_favicon',
        'app_description',
        'app_keywords',
        'app_logo',
        'app_title',
        'app_short_description',
        'address',
        'phone',
        'email',
        'mobile',
        'fax',
        'telegram',
        'whatsapp',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'linkedin',
        'pinterest',
        'github',
    ];

}
