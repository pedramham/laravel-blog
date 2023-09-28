<?php

namespace Admin\ApiBolg\Http\Requests\Video;

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
            'duration' => 'string|nullable',
            'video_number' => 'string|nullable',
            'video_url' => 'string|nullable',
            'priority' => 'integer|nullable',
            'video_course_id' => 'nullable|exists:video_courses,id',
            'tags' => 'array|nullable',
            'tags.id.*' => 'required|exists:tags,id',
            'tags.name.*' => 'required|unique:tags',
            'media' => 'array|nullable',
            'media.image.thumbnail' => 'nullable|mimes:jpeg,jpg,png,webp|max:10000',
            'media.file.video_file' => 'nullable',
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
