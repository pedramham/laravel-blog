<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'slug',
        'subject',
        'description',
        'pic_small',
        'pic_large',
        'category_id',
    ];
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->as('tags');
    }
}
