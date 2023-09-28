<?php

namespace Admin\ApiBolg\Facades;

use Admin\ApiBolg\Helper\FileHelper;
use Illuminate\Support\Facades\Facade;
use Symfony\Component\HttpFoundation\FileBag;

/**
 * @method static array storeFile(array $input)
 * @method static void deleteFile(array $filenames, string $folderName)
 */
class Files extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FileHelper::class;
    }
}
