<?php

namespace Admin\ApiBolg\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skip' => 'integer|required|min:0',
            'take' => 'integer|required|min:1',
        ];
    }
}
