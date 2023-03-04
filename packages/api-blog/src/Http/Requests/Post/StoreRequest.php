<?php

namespace Admin\ApiBolg\Http\Requests\Post;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'tags' => 'array|nullable',
            'tags.id.*' => 'required|exists:tags,id',
            'tags.name.*' => 'required|unique:tags',
        ]);
    }
}
