<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Facades\Files;
use Admin\ApiBolg\Http\Requests\Post\EditRequest;
use Admin\ApiBolg\Http\Requests\Post\PostRequest;
use Admin\ApiBolg\Http\Requests\Post\StoreRequest;
use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Models\Tag;
use Admin\ApiBolg\Traits\HelperTrait;
use Admin\ApiBolg\Traits\ModelTrait;
use Symfony\Component\HttpFoundation\FileBag;

class PostService
{
    use ModelTrait;
    use HelperTrait;

    public function show(array $input, $model)
    {
        return $model::with('tags', 'category')->get()->find($input['id']);
    }

    public function storePost(StoreRequest $request): array
    {
        $input = $request->validated();
        $input = Files::storeFile($request->files ,$input, $input['post_type']);

        $post = $this->store($input, Post::class);
        $this->storeTags($post, $input);

        return array_merge(
            $post->toArray(),
            ['tags' => $input['tags'] ?? null]
        );
    }


    public function deletePost(PostRequest $request): string|bool
    {
        $input = $request->validated();
        return $this->deleteModelWithFiles(Post::class, $input, $input['post_type']);
    }

    public function updatePost(EditRequest $request): string|array
    {

        $input = $request->validated();
        $post = Post::class::findOrFail($input['id']);
        //get filename pic_large and pic_small from post to delete old file
        $filename = $post->only('pic_large', 'pic_small');
        //delete old file and upload new file in storage
        $input = $this->updatePotFiles($request->files, $input, $filename);
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


    private function updatePotFiles(FileBag $request,array $input,array $filename): array|string
    {
        return $this->updateFile($request, $input, $filename, $input['post_type']);
    }
}
