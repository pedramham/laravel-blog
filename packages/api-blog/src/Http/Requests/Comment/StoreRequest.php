<?php

namespace Admin\ApiBolg\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'comments' => 'required|string',
            'status' => 'nullable|boolean',
            'post_id' => 'required|integer|exists:posts,id',
        ];
    }
}
