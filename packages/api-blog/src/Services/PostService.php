<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Facades\Files;
use Admin\ApiBolg\Http\Requests\Post\EditRequest;
use Admin\ApiBolg\Http\Requests\Post\PostRequest;
use Admin\ApiBolg\Http\Requests\Post\StoreRequest;
use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Models\Tag;
use Admin\ApiBolg\Models\Video;
use Admin\ApiBolg\Traits\HelperTrait;
use Admin\ApiBolg\Traits\ModelTrait;
use Symfony\Component\HttpFoundation\FileBag;

class PostService
{
    use ModelTrait;
    use HelperTrait;

    public function show(array $input, $model)
    {
        return $model::with('tags', 'category', 'videos')->get()->find($input['id']);
    }

    public function storePost(array $input): array
    {
     
        $input = Files::storeFile($input);
      //  dd($input);
        $post = $this->store($input, Post::class);
        $this->storeTags($post, $input);
        $this->storeVideo($post, $input);
        return array_merge(
            $post->toArray(),
            ['tags' => $input['tags'] ?? null]
        );
    }


    public function deletePost(PostRequest $request): string|bool
    {
        $input = $request->validated();
        return $this->deleteModelWithFiles(Post::class, $input);
    }

    public function updatePost(array $input): string|array
    {

        $post = Post::class::findOrFail($input['id']);
        //get filename pic_large and pic_small from post to delete old file
        $filename = $post->only('pic_large', 'pic_small', 'file');
  
        if (isset($input['video']['file'])) {
            if(!isset($input['media']))
            { 
                $input['media'] = [];
              
            }
            $input['media'] = array_merge($input['media'], $input['video']);
        
        }
        dd($input);
        $input = array_merge($input, $this->updateFile($input, $filename));
  

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

    public function storeTags(Post $post, array $input, bool $update = false): void
    {
        //if request not has tags return null
        if (!isset($input['tags'])) {
            return;
        }

        if ($update) {
            $post->tags()->sync([]);
        }
    
        foreach ($input['tags'] as $tagName) {
            //find tag by name
            $tag = Tag::where('name', $tagName['name'])->first();
            //if tag not exist create new tag
            if (!$tag) {
                $tag = Tag::firstOrCreate(['name' => $tagName['name']]);
            }

            $post->tags()->attach($tag);
        }
    }

    public function storeVideo(Post $post, array $input, bool $update = false): void
    {
        //if request not has video return null
        if ( !isset($input['file']) && !isset(['media']['video']['url']) && !isset(['media']['video'])) return;  
    
        if ($update) {
            $post->videos()->sync([]);
        }
        
        $input=[
           'likes' => $input['media']['video']['likes']?? 0,
           'views' => $input['media']['video']['views'] ?? 0,
           'file'  => $input['file'] ?? null,
           'url'  => $input['media']['video']['url'] ?? null,
        ];
     
        $video = Video::create($input);
        $post->videos()->attach($video);

    }
}
