<?php

namespace Admin\ApiBolg\Http\Requests\Post;

class EditRequest extends BaseRequest
{

    public function rules(): array
    {
        return array_merge(parent::rules(),
            [
                'id' => 'integer|required|exists:categories,id'
            ]
        );
    }
}
