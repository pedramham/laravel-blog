<?php

namespace Admin\ApiBolg\Http\Requests\VideoCourse;

use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => 'string|required',
            'title' => 'string|required',
            'video_category_id' => 'required|exists:video_categories,id',
            'slug' => 'string|required|unique_translation:video_courses',
            'status' => ['required',Rule::in(Config::get('app.CATEGORY_STATUS'))],
            'media' => 'nullable',
            'folder_name' => 'string|required',
        ]);
    }
}
