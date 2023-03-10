<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Helper\FileHelper;
use Admin\ApiBolg\Http\Requests\Post\EditRequest;
use Admin\ApiBolg\Http\Requests\Post\PostRequest;
use Admin\ApiBolg\Http\Requests\Post\StoreRequest;
use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Models\Tag;
use Admin\ApiBolg\Traits\ModelTrait;

class PostService extends FileHelper
{
    use ModelTrait;


    public function show(array $input, $model)
    {
        return $model::with('tags', 'category')->get()->find($input['id']);
    }

    public function storePost(StoreRequest $request): array
    {
        $input = $request->validated();
        $input = $this->storeAndSetPic($request, $input);

        $post = $this->store($input, Post::class);
        $this->storeTags($post, $input);

        return array_merge(
            $post->toArray(),
            ['tags' => $input['tags'] ?? null]
        );
    }


    public function deletePost(PostRequest $request): string|bool
    {
        //Get filename pic_large and pic_small from post
        $filename = Post::withTrashed()->find($request['id'])->only('pic_large', 'pic_small');
        $input = $request->validated();

        try {
            //name folder is declared according to the post_type
            $this->deleteFile($input['post_type'], $filename);
            return $this->delete($input, Post::class);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function updatePost(EditRequest $request): string|array
    {
        $input = $request->validated();
        $post = Post::class::findOrFail($input['id']);
        $filename = $post->only('pic_large', 'pic_small');

        $input = $this->updateFiles($request, $input, $filename);

        $this->edit($input, $post);
        $this->storeTags($post, $input, true);
        //return post with tags
        return array_merge(
            $post->toArray(),
            ['tags' => $input['tags'] ?? null]
        );
    }

    public function storeTags(Post $post, array $request, bool $update = false): void
    {
        //if request not has tags return null
        if (!isset($request['tags'])) {
            return;
        }

        if ($update) {
            $post->tags()->sync([]);
        }

        foreach ($request['tags'] as $tagName) {
            //find tag by name
            $tag = Tag::where('name', $tagName['name'])->first();
            //if tag not exist create new tag
            if (!$tag) {
                $tag = Tag::firstOrCreate(['name' => $tagName['name']]);
            }

            $post->tags()->attach($tag);
        }
    }

    private function storeAndSetPic(StoreRequest|EditRequest $request, array $input): array
    {
        //post_type is used to name the folder where the images will be stored
        $pics = $this->storeFile($request->files, $input['post_type']);

        //set the new name of the images in the input array to be stored in the database
        //we do this because the name of the images is generated randomly
        foreach ($pics as $key => $pic) {
            $input[$key] = $pic ?? null;
        }
        return $input;
    }

    private function updateFiles($request, $input, $filename): array|string
    {
        //if request not has file pic_small or pic_large return input
        if (!$request->hasFile('pic_small') || !$request->hasFile('pic_large')) {
            return $input;
        }

        try {
            //If request has file pic_small or pic_large delete old file and store new file
            $this->deleteFile($input['post_type'], $filename);
            return $this->storeAndSetPic($request, $input);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
