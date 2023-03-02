<?php

namespace Admin\ApiBolg\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'integer|required|exists:categories,id',
            'name' => 'string|nullable',
            'title' => 'string|nullable',
            'slug' => 'string|nullable',
            'subject' => 'string|nullable',
            'description' => 'string|nullable',
            'pic-small' => 'string|nullable',
            'pic-large' => 'string|nullable',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }
}
