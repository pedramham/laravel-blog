<?php

namespace Admin\ApiBolg\Services;

use Admin\ApiBolg\Facades\Files;
use Admin\ApiBolg\Http\Requests\Category\CategoryRequest;
use Admin\ApiBolg\Http\Requests\Category\EditRequest;
use Admin\ApiBolg\Http\Requests\Category\StoreRequest;
use Admin\ApiBolg\Http\Requests\Post\PostRequest;
use Admin\ApiBolg\Models\Category;
use Admin\ApiBolg\Traits\HelperTrait;
use \Admin\ApiBolg\Traits\ModelTrait;
use Symfony\Component\HttpFoundation\FileBag;

class CategoryService
{
    use ModelTrait;
    use HelperTrait;

    public function storeCategory(array $input): array
    {
        $input = Files::storeFile($input);
        $post = $this->store($input, Category::class);
        return $post->toArray();
    }

    public function updateCategory(array $input): string|array
    {

        $category = Category::class::findOrFail($input['id']);
        //get filename pic_large and pic_small from category to delete old file
        $filenames = $category->only('pic_large', 'pic_small');
        //delete old file and upload new file in storage
        $input = $this->updateFile($input, $filenames);
      
        //after delete old file and upload new file in storage update post
        $this->edit($input, $category);
        //return post with tags
        return $category->toArray();
    }

    public function deleteCategory(array $input): string|bool
    {
        return $this->deleteModelWithFiles(Category::class, $input);
    }

}
