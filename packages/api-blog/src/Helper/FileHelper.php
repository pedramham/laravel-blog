<?php

namespace Admin\ApiBolg\Helper;

use Illuminate\Support\Facades\File;
use Log;
use Symfony\Component\HttpFoundation\FileBag;

class FileHelper implements FileHelperInterface
{
    protected static function getFacadeAccessor()
    {
        return 'fileHelper';
    }

    public function storeFile(array $input): array
    {
        //if request has media upload file in storage
        if (!isset($input['media'])) {
            return $input;
        }

        //path is the folder where the images are stored
        $path = config('app.ROOT_PATH_STORAGE_FILE_API_BLOG') . '/' . $input['folder_name'];

        $pics = $this->UploadFile($input, $path);
        //set the new name of the images in the input array to be stored in the database
        //we do this because the name of the images is generated randomly
        foreach ($pics as $key => $pic) {
            $input[$key] = $pic ?? null;
        }
        unset($input['media']);
        return $input;
    }

    private function UploadFile(array $input, string $path): array
    {

        foreach ($input['media'] as $keys => $files) {

            foreach ($files as $key => $file) {

                if(!is_file($file)) {
                    break;
                }
                //get the extension of the image for example: jpg, png, etc.
                $extension = File::extension($file->getClientOriginalName());

                //PREFIX_IMAGE is a variable that is stored in the .env file and is used to generate the name of the images
                $fileName = $_ENV['PREFIX_IMAGE'] ?? '' . '_' . rand(1, 1000) . '.' . $extension;
                //public_path is a function that returns the path of the public folder of the project and the path is the folder where the images are stored
                try {
                $file->move(public_path($path), $fileName);
                } catch(\Exception $e) {

                    throw $e;
                }
                $input[$key] = $fileName;

            }
        }

        return $input;
    }

    public function deleteFile(array $filenames, string $folderName): void
    {

        //typeFolder is the folder where the images are stored
        $path = config('app.ROOT_PATH_STORAGE_FILE_API_BLOG') . '/' . $folderName;
        foreach ($filenames as $fileName) {
            $file = public_path($path . '/' . $fileName);
            if (File::exists($file)) {
                File::delete($file);
            }
        }
    }
}
