<?php

namespace Admin\ApiBolg\Http\Requests\Video;

class StoreRequest extends BaseRequest
{

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => 'string|required',
            'title' => 'string|required',
            'slug' => 'string|required|unique_translation:videos',
            'media' => 'nullable',
            'folder_name' => 'string|required',
        ]);
    }
}
