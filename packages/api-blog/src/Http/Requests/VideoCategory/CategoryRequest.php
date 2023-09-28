<?php

namespace Admin\ApiBolg\Http\Requests\VideoCategory;

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
            'id' => 'integer|required|exists:video_categories,id',
            'status' => 'string|nullable',
            'parent_id' => 'string|nullable',
        ];
    }
}
