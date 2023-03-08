<?php

namespace Admin\ApiBolg\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:posts,id',
            'name' => 'nullable|string|min:3|max:255',
            'email' => 'nullable|email',
            'comments' => 'nullable|string',
            'status' => 'nullable|boolean',
        ];
    }
}
