<?php

namespace Admin\ApiBolg\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string',
            'title' => 'string|nullable',
            'status' => 'string|required_if:bak_leaflet,draft',
            'subject' => 'string|nullable',
            'description' => 'string|nullable',
            'meta_description' => 'string|nullable',
            'meta_keywords' => 'string|nullable',
            'meta_language' => 'string|nullable',
            'tweet_text' => 'string|nullable',
            'issue_type' => 'string|required',
            'menu_order' => 'integer|nullable',
            'priority' => 'integer|nullable',
            'menu_status' => 'bool|nullable',
            'visible_index_status' => 'bool|nullable',
            'local' => 'string|required',
            'media.pic_small' => 'nullable|mimes:jpeg,jpg,png,webp|max:10000',
            'media.pic_large' => 'nullable|mimes:jpeg,jpg,png,webp|max:10000',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    public function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'success' => false,
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
        ],  JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }
}
