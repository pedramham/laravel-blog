<?php

namespace Admin\ApiBolg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
        'parent_id',
    ];
}
