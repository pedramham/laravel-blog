<?php

namespace Admin\ApiBolg\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|nullable',
            'status' => 'string|required_if:bak_leaflet,draft',
            'subject' => 'string|nullable',
            'description' => 'string|nullable',
            'meta_description' => 'string|nullable',
            'meta_keywords' => 'string|nullable',
            'meta_language' => 'string|nullable',
            'tweet_text' => 'string|nullable',
            'category_type' => 'string|required',
            'menu_order' => 'integer|nullable',
            'priority' => 'integer|nullable',
            'menu_status' => 'bool|nullable',
            'visible_index_status' => 'bool|nullable',
            'pic_small' => 'nullable|mimes:jpeg,jpg,webp|max:10000',
            'pic_large' => 'nullable|mimes:jpeg,jpg,png,webp|max:99000',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }
}
