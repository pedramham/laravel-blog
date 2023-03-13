<?php

namespace Admin\ApiBolg\Helper;

use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\FileBag;

class  FileHelper implements FileHelperInterface
{
    protected static function getFacadeAccessor()
    {
        return 'fileHelper';
    }

    const PATH = 'api-blog';

    public function storeFile(array $input, $path): array
    {
        //path is the folder where the images are stored
        $path = self::PATH . '/' . $path;
        $pics = $this->UploadFile($input, $path);
        //set the new name of the images in the input array to be stored in the database
        //we do this because the name of the images is generated randomly
        foreach ($pics as $key => $pic) {
            $input[$key] = $pic ?? null;
        }

        return $input;
    }

    private function UploadFile(array $request, string $path): array
    {
        $files = [];

        foreach ($request as $key => $file) {
            //get the extension of the image for example: jpg, png, etc.
            $extension = File::extension($file->getClientOriginalName());
            //PREFIX_IMAGE is a variable that is stored in the .env file and is used to generate the name of the images
            $fileName = $_ENV['PREFIX_IMAGE'] . '_' . rand(1, 1000) . '.' . $extension;
            //public_path is a function that returns the path of the public folder of the project and the path is the folder where the images are stored
            $file->move(public_path($path), $fileName);
            $files[$key] = $fileName;
        }

        return $files;
    }

    public function deleteFile(string $typeFolder, array $filenames): void
    {
        //typeFolder is the folder where the images are stored
        $path = self::PATH . '/' . $typeFolder;
        foreach ($filenames as $fileName) {
            $file = public_path($path . '/' . $fileName);
            if (File::exists($file)) {
                File::delete($file);
            }
        }
    }

}
