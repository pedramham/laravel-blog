<?php

namespace Admin\ApiBolg\Http\Requests\Category;

use Admin\ApiBolg\Models\Category;
use Illuminate\Validation\Rule;

class EditRequest extends BaseRequest
{

    public function rules(): array
    {
        return array_merge(parent::rules(),
            [
                'id' => 'integer|required|exists:categories,id',
                'title' => 'string|nullable',
                'slug' => "alpha_dash|nullable|unique:categories,slug,$this->id",
                'status' => ['required',Rule::in(Category::Category_STATUS)]
            ]
        );
    }
}
