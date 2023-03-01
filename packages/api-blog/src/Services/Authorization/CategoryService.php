<?php

namespace Admin\ApiBolg\Services\Authorization;

use Admin\ApiBolg\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class CategoryService
{
    public function store(array $input): Category
    {
        return Category::create($input);
    }

    public function edit(array $input): bool
    {
        return  Category::whereId($input['id'])->update($input);
    }

    public function show(array $input): Category
    {
        return  Category::find($input['id']);
    }

    public function list(): Collection
    {
        return  Category::all();
    }
}
