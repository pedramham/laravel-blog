<?php

namespace Admin\ApiBolg\Helper;

use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\FileBag;
use Illuminate\Support\Facades\Facade;

class FileHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'fileHelper';
    }

    const PATH = 'api-blog';

    public function storeFile(FileBag $request, string $path): array
    {
        $path = self::PATH . '/' . $path;

        return $this->UploadFile($request, $path);
    }

    private function UploadFile($request, string $path): array
    {
        $files = [];

        foreach ($request as $key => $file) {
            $extension = File::extension($file->getClientOriginalName());
            $fileName = $_ENV['PREFIX_IMAGE'] . '_' . rand(10, 99) . '.' . $extension;
            $file->move(public_path($path), $fileName);
            $files[$key] = $fileName;
        }

        return $files;
    }

    public function deleteFile(string $typeFolder, array $filenames): void
    {
        $path = self::PATH . '/' . $typeFolder;
        foreach ($filenames as $fileName) {
            $file = public_path($path . '/' . $fileName);
            if (File::exists($file)) {
                File::delete($file);
            }
        }
    }


}
