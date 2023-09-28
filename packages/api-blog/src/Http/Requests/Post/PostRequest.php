<?php

namespace Admin\ApiBolg\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PostRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'integer|required|exists:posts,id',
            'list_trash' => 'string|nullable',
            'issue_type' => 'string|nullable',
            'folder_name' => 'string|required',
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
