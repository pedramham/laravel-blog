<?php

namespace Admin\ApiBolg\Http\Requests\Video;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tags' => 'nullable',
            'local' => 'string|required',
            'list_trash' => 'string|nullable',
        ];
    }
}
