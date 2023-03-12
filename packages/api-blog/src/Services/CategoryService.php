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

    public function storeCategory(StoreRequest $request): array
    {
        $input = $request->validated();
        $input = Files::storeFile($request->files, $input, $input['category_type']);
        $post = $this->store($input, Category::class);
        return $post->toArray();
    }

    public function updateCategory(EditRequest $request): string|array
    {

        $input = $request->validated();
        $category = Category::class::findOrFail($input['id']);
        //get filename pic_large and pic_small from category to delete old file
        $filename = $category->only('pic_large', 'pic_small');
        //delete old file and upload new file in storage
        $input = $this->updateCategoryFiles($request->files, $input, $filename);
        //after delete old file and upload new file in storage update post
        $this->edit($input, $category);
        //return post with tags
        return $category->toArray();
    }

    public function deleteCategory(CategoryRequest $request): string|bool
    {
        $input = $request->validated();
        return $this->deleteModelWithFiles(Category::class, $input, $input['category_type']);
    }

    private function updateCategoryFiles(FileBag $request, array $input, array $filename): array|string
    {
        $this->updateFile($request, $input, $filename, $input['category_type']);
    }

}
