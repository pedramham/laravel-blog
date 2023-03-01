<?php

namespace Admin\ApiBolg\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|required',
            'title' => 'string|required',
            'slug' => 'string|required|unique:posts',
            'subject' => 'string|nullable',
            'description' => 'string|nullable',
            'pic_small' => 'string|nullable',
            'pic_large' => 'string|nullable',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }
}
