<?php

namespace Admin\ApiBolg\Facades;

use Admin\ApiBolg\Helper\FileHelper;
use Illuminate\Support\Facades\Facade;

class Files extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FileHelper::class;
    }
}
