<?php

namespace Admin\ApiBolg\Http\Requests\VideoCourse;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'integer|required|exists:video_courses,id',
        ];
    }
}
