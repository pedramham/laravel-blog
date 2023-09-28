<?php

namespace Admin\ApiBolg\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'integer|required|exists:categories,id',
            'folder_name' => 'string|nullable',
        ];
    }
}
