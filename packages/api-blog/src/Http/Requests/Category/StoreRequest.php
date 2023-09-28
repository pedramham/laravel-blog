<?php

namespace Admin\ApiBolg\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Admin\ApiBolg\Models\Category;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'slug' => 'string|required|unique_translation:categories',
            'folder_name' => 'string|required',
            'status' => ['required',Rule::in(Config::get('app.CATEGORY_STATUS'))],
            'media' => 'nullable',
        ]);
    }
}
