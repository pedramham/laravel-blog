<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Facades\Files;
use Admin\ApiBolg\Http\Requests\Category\CategoryRequest;
use Admin\ApiBolg\Http\Requests\Category\EditRequest;
use Admin\ApiBolg\Http\Requests\Category\StoreRequest;
use Admin\ApiBolg\Http\Requests\Post\PostRequest;
use Admin\ApiBolg\Models\Category;
use Admin\ApiBolg\Models\VideoCategory;
use Admin\ApiBolg\Models\VideoCourse;
use Admin\ApiBolg\Traits\HelperTrait;
use \Admin\ApiBolg\Traits\ModelTrait;

use Log;
use Symfony\Component\HttpFoundation\FileBag;

class VideoCourseService
{
    use ModelTrait;
    use HelperTrait;

    /*
     * This function takes an array of input data,
     *  stores a file if it exists, creates a new category with the input data,
     *  and returns the category as an array
     */
    public function storeVideoCourse(array $input): array
    {

        $data = Files::storeFile($input);
        $post = $this->store($data, VideoCourse::class);
        return $post->toArray();
    }

    public function updateVideoCourse(array $input): string|array
    {

        $videoCourse = VideoCourse::class:: findOrFail($input['id']);
        //get filename pic_large and pic_small from category to delete old file
        $filenames = $videoCourse->only($this->identifyAttachFilesInInputRequest($input));
        //delete old file and upload new file in storage
        if (!empty($filenames)) {
            $input = $this->updateFile($input, $filenames);
        }

        //after delete old file and upload new file in storage update post
        $this->edit($input, $videoCourse);
        //return post with tags
        return $videoCourse->toArray();
    }

    public function deleteVideoCourse(array $input): string|bool
    {
        return $this->deleteModelWithFiles(VideoCourse::class, $input);
    }

}
