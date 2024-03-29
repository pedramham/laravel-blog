<?php

namespace Admin\ApiBolg\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'integer|required|exists:categories,id',
            'issue_type' => 'string|nullable',
            'folder_name' => 'string|nullable',
            'status' => 'string|nullable',
            'parent_id' => 'string|nullable',
        ];
    }
}
