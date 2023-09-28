<?php

namespace Admin\ApiBolg\Http\Requests\Category;

use Admin\ApiBolg\Models\Category;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EditRequest extends BaseRequest
{

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'id' => 'integer|required|exists:categories,id',
            'folder_name' => 'string|required',
            'slug' => "alpha_dash|nullable|unique_translation:categories,slug,$this->id",
            'media' => 'nullable',
        ]);
    }
}
