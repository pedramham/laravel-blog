<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Models\Tag;
use Admin\ApiBolg\Traits\ModelTrait;

class PostService
{
    use ModelTrait;

    public function show(array $input, $model)
    {
        return  $model::with('tags','category')->get()->find($input['id']);
    }

    public function storePost(array $request): array
    {
        $post = $this->store($request, Post::class);
        $this->storeTags($post, $request);

        return array_merge(
            $post->toArray(),
            ['tags' => $request['tags'] ?? null]
        );
    }

    public function updatePost(array $request): array
    {
        $this->edit($request, Post::class);
        $post = Post::class::findOrFail($request['id']);

        $this->storeTags($post, $request,true);

        return array_merge(
            $post->toArray(),
            ['tags' => $request['tags'] ?? null]
        );
    }

    public function storeTags($post, $request, $update = false): void
    {
        if (!isset($request['tags'])) {
            return;
        }

        if ($update)
        {
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
}
