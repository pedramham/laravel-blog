<?php

namespace Admin\ApiBolg\Http\Requests\VideoCourse;

use Admin\ApiBolg\Models\Category;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EditRequest extends BaseRequest
{

    public function rules(): array
    {
        return array_merge(parent::rules(),
            [
                'id' => 'integer|required|exists:video_courses,id',
                'name' => 'string|nullable',
                'title' => 'string|nullable',
                'slug' => "alpha_dash|nullable|unique_translation:video_courses,slug,$this->id",
                'folder_name' => 'string|required',
                'media' => 'nullable',
            ]
        );
    }
}
