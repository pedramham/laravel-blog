<?php

namespace Admin\ApiBolg\Http\Requests\Video;

class EditRequest extends BaseRequest
{

    public function rules(): array
    {
        return array_merge(parent::rules(),
            [
                'name' => 'string|nullable',
                'title' => 'string|nullable',
                'folder_name' => 'string|required',
                'video_course_id' => 'integer|nullable|exists:video_courses,id',
                'id' => 'integer|nullable|exists:videos,id',
                'slug' => "alpha_dash|nullable|unique_translation:videos,slug,$this->id",
                'media' => 'nullable',
            ]
        );
    }
}
