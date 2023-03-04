<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Models\Tag;
use Admin\ApiBolg\Traits\ModelTrait;

class PostService
{
    use ModelTrait;
    public function storePost($request)
    {
        $post = $this->store($request, Post::class);

        //if post not exist do not attach tags
        if (!isset($request['tags'])){
            return $post;
        }

        foreach($request['tags'] as $tagName)
        {
            //find tag by name
            $tag = Tag::where('name', $tagName)->first();
            //if tag not exist create new tag
            if(!$tag)
            {
                $tag = Tag::firstOrCreate(['name' => $tagName['name']]);
            }
            //attach tag to post
            $post->tags()->attach($tag);
        }

        //return post with tags
        return array_merge($post->toArray(), ['tags' => $tag->toArray()]);
    }
}
