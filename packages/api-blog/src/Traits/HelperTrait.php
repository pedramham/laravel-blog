<?php

namespace Admin\ApiBolg\Traits;

use Admin\ApiBolg\Facades\Files;

use Log;

trait HelperTrait
{

    public function deleteModelWithFiles($model, array $input): string|bool
    {
        //Get filename pic_large and pic_small from post
        $filenames = $model::withTrashed()->find($input['id'])->only('pic_large', 'pic_small',"thumbnail","video_file");

        try {
            //name folder is declared according to the issue_type
            if (max($filenames) !== null) {
                Files::deleteFile($filenames, $input['folder_name']);
            }

            $this->delete($input, $model);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateFile(array $input, ?array $filenames): array|string
    {

        try {
            //If request has file pic_small or pic_large delete old file and store new file
            if (max($filenames) !== null) {

                Files::deleteFile($filenames, $input['folder_name']);
            }

            return Files::storeFile($input);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function identifyAttachFilesInInputRequest(array $input): array
    {

        if (isset($input["media"]["'image'"]))
        {
            $input["media"]["image"] = $input["media"]["'image'"];
        }

        $deleteFile =[];
        $keys = ["pic_large", "pic_small","thumbnail","video_file"];
        foreach ($keys as $key) {
            if (isset($input["media"]["image"][$key])) {
                $deleteFile[] = $key;
            }
        }
        return $deleteFile;
    }
}
