<?php

namespace Admin\ApiBolg\Http\Requests\Post;

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
            'name' => 'string|required',
            'title' => 'string|required',
            'status' => 'string|required_if:bak_leaflet,draft',
            'slug' => 'string|required|unique:posts',
            'subject' => 'string|nullable',
            'description' => 'string|nullable',
            'meta_description' => 'string|nullable',
            'meta_keywords' => 'string|nullable',
            'meta_language' => 'string|nullable',
            'tweet_text' => 'string|nullable',
            'post_type' => 'string|nullable',
            'menu_order' => 'integer|nullable',
            'priority' => 'integer|nullable',
            'comment_status' => 'bool|nullable',
            'menu_status' => 'bool|nullable',
            'visible_index_status' => 'bool|nullable',
            'pic_small' => 'string|nullable',
            'pic_large' => 'string|nullable',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }
}