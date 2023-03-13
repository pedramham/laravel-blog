<?php

namespace Admin\ApiBolg\Facades;

use Admin\ApiBolg\Helper\FileHelper;
use Illuminate\Support\Facades\Facade;
use Symfony\Component\HttpFoundation\FileBag;

/**
 * @method static array storeFile(array $input, string $path)
 * @method static void deleteFile(string $typeFolder, array $filenames)
 */
class Files extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FileHelper::class;
    }
}
