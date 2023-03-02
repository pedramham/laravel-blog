<?php

namespace Admin\ApiBolg\Http\Requests\Category;

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
