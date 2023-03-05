<?php

namespace Admin\ApiBolg\Http\Requests\Post;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => 'string|required',
            'title' => 'string|required',
            'slug' => 'string|required|unique:posts',
        ]);
    }
}
