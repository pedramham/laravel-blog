<?php

namespace Admin\ApiBolg\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'integer|required|exists:comment,id',
        ];
    }
}
