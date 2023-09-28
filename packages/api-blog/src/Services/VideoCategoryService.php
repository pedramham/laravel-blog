<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Facades\Files;
use Admin\ApiBolg\Http\Requests\Category\CategoryRequest;
use Admin\ApiBolg\Http\Requests\Category\EditRequest;
use Admin\ApiBolg\Http\Requests\Category\StoreRequest;
use Admin\ApiBolg\Http\Requests\Post\PostRequest;
use Admin\ApiBolg\Models\Category;
use Admin\ApiBolg\Models\VideoCategory;
use Admin\ApiBolg\Traits\HelperTrait;
use \Admin\ApiBolg\Traits\ModelTrait;

use Log;
use Symfony\Component\HttpFoundation\FileBag;

class VideoCategoryService
{
    use ModelTrait;
    use HelperTrait;

    /*
     * This function takes an array of input data,
     *  stores a file if it exists, creates a new category with the input data,
     *  and returns the category as an array
     */
    public function storeCategory(array $input): array
    {

        $data = Files::storeFile($input);
        $post = $this->store($data, VideoCategory::class);
        return $post->toArray();
    }

    public function updateVideoCategory(array $input): string|array
    {

        $videoCategory = VideoCategory::class:: findOrFail($input['id']);
        //get filename pic_large and pic_small from category to delete old file
        $filenames = $videoCategory->only($this->identifyAttachFilesInInputRequest($input));
        //delete old file and upload new file in storage
        if (!empty($filenames)) {
            $input = $this->updateFile($input, $filenames);
        }

        //after delete old file and upload new file in storage update post
        $this->edit($input, $videoCategory);
        //return post with tags
        return $videoCategory->toArray();
    }

    public function deleteCategory(array $input): string|bool
    {
        return $this->deleteModelWithFiles(VideoCategory::class, $input);
    }

}
