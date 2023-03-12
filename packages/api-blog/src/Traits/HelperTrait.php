<?php

namespace Admin\ApiBolg\Traits;

use Admin\ApiBolg\Facades\Files;
use Symfony\Component\HttpFoundation\FileBag;

trait HelperTrait
{

    public function deleteModelWithFiles($model, array $input, string $type): string|bool
    {
        //Get filename pic_large and pic_small from post
        $filename = $model::withTrashed()->find($input['id'])->only('pic_large', 'pic_small');
        try {
            //name folder is declared according to the post_type
            Files::deleteFile($type, $filename);
            return $this->delete($input, $model);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function updateFile(FileBag $request, array $input, array $filename, string $type): array|string
    {

        //if request not has file pic_small or pic_large return input
        if (!$request->get('pic_small') && !$request->get('pic_large')) {
            return $input;
        }

        try {
            //If request has file pic_small or pic_large delete old file and store new file
            Files::deleteFile($type, $filename);
            return Files::storeFile($request, $input, $type);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
