<?php

namespace Admin\ApiBolg\Http\Requests\Post;

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
            'comment_status' => 'bool|nullable',
            'menu_status' => 'bool|nullable',
            'visible_index_status' => 'bool|nullable',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array|nullable',
            'tags.id.*' => 'required|exists:tags,id',
            'tags.name.*' => 'required|unique:tags',
            'media' => 'array|nullable',
            'media.image.pic_small' => 'nullable|mimes:jpeg,jpg,png,webp|max:10000',
            'media.image.pic_large' => 'nullable|mimes:jpeg,jpg,png,webp|max:99000',
            'local' => 'string|required',
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
