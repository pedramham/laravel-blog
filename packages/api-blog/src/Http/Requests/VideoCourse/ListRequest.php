<?php

namespace Admin\ApiBolg\Http\Requests\VideoCourse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class ListRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'string|nullable',
            'local' => 'string|required',
            'list_trash' => 'string|nullable',
        ];
    }

    public function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
