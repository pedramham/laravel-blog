<?php

namespace Admin\ApiBolg\Http\Requests\VideoCategory;

use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'slug' => 'string|required|unique_translation:video_categories',
            'status' => ['required',Rule::in(Config::get('app.CATEGORY_STATUS'))],
            'media' => 'nullable',
        ]);
    }
}
