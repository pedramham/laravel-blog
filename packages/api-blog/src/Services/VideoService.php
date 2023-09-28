<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Facades\Files;
use Admin\ApiBolg\Http\Requests\Post\DeletePost;
use Admin\ApiBolg\Http\Requests\Post\DeletePostRequest;
use Admin\ApiBolg\Http\Requests\Post\EditRequest;
use Admin\ApiBolg\Http\Requests\Post\PostRequest;
use Admin\ApiBolg\Http\Requests\Post\StoreRequest;
use Admin\ApiBolg\Http\Requests\Video\DeleteVideo;
use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Models\Tag;
use Admin\ApiBolg\Models\Video;
use Admin\ApiBolg\Traits\HelperTrait;
use Admin\ApiBolg\Traits\ModelTrait;
use Log;
use OpenApi\Attributes\Info;
use Symfony\Component\HttpFoundation\FileBag;

class VideoService
{
    use ModelTrait;
    use HelperTrait;

    public function show(array $input, string $model)
    {
        /* @var $model Video */
        return $model::with('tags', 'videoCourse:id,name')->get()->find($input['id']);
    }

    public function storeVideo(array $input): array
    {

        $input = Files::storeFile($input);

        $post = $this->store($input, Video::class);
        $this->storeTags($post, $input);

        return array_merge(
            $post->toArray(),
            ['tags' => $input['tags'] ?? null]
        );
    }


    public function deleteVideo(DeleteVideo $request): string|bool
    {
        $input = $request->validated();
        return $this->deleteModelWithFiles(Video::class, $input);
    }

    public function updateVideo(array $input): string|array
    {

        /* @var $post Video */
        $post = Video::class::findOrFail($input['id']);


        if (!isset($input['media'])) {
            $input['media'] = [];
        }

        //get filename pic_large and pic_small from post to delete old file
        $filename = $post->only($this->identifyAttachFilesInInputRequest($input));

        if (!empty($filename)) {
            $input = array_merge($input, $this->updateFile($input, $filename));
        }

        //after delete old file and upload new file in storage update post
        $this->edit($input, $post);
        //update tags related to post
        $this->storeTags($post, $input, true);
        //return post with tags
        return array_merge(
            $post->toArray(),
            ['tags' => $input['tags'] ?? null]
        );
    }

    public function storeTags(Video $video, array $input, bool $update = false): void
    {
        //if request not has tags return null
        if (!isset($input['tags'])) {
            return;
        }

        if ($update) {
            $video->tags()->sync([]);
        }
        foreach ($input['tags'] as $tagName) {
            //find tag by name
            $tag = Tag::where('id', $tagName['name'])->first();
            //if tag not exist create new tag
            if (!$tag) {
                $tag = Tag::firstOrCreate(['name' => $tagName['name']]);
            }

            $video->tags()->attach($tag);
        }
    }

}
