<?php

namespace Admin\ApiBolg\Http\Requests\Post;

class EditRequest extends BaseRequest
{

    public function rules(): array
    {
        return array_merge(parent::rules(),
            [
                'name' => 'string|nullable',
                'title' => 'string|nullable',
                'category_id' => 'integer|nullable|exists:categories,id',
                'id' => 'integer|required|exists:posts,id',
                'slug' => "alpha_dash|nullable|unique:posts,slug,$this->id",
            ]
        );
    }
}
