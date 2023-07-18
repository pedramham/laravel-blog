<?php

namespace Admin\ApiBolg\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Admin\ApiBolg\Models\Category;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'title' => 'string|required',
            'slug' => 'string|required|unique:categories',
            'status' => ['required',Rule::in(Category::Category_STATUS)]
        ]);
    }
}
