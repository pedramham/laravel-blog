<?php

namespace Admin\ApiBolg\Http\Requests\Category;

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
            'name' => 'string|nullable',
            'title' => 'string|required',
            'slug' => 'string|required|unique:categories',
            'subject' => 'string|nullable',
            'description' => 'string|nullable',
            'pic_small' => 'string|nullable',
            'pic_large' => 'string|nullable',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }
}
