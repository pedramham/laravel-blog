<?php

namespace Admin\ApiBolg\Http\Requests\Post;

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
            'issue_type' => 'string|nullable',
            'tags' => 'nullable',
            'local' => 'string|required',
            'list_trash' => 'string|nullable',
        ];
    }
}
