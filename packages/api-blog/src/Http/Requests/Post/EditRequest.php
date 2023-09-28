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
                'folder_name' => 'string|required',
                'category_id' => 'integer|exists:categories,id|nullable',
                'id' => 'integer|nullable|exists:posts,id',
                'slug' => "alpha_dash|nullable|unique_translation:posts,slug,$this->id",
                'media' => 'nullable',
            ]
        );
    }
}
