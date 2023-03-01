<?php

namespace Admin\ApiBolg\Http\Requests\AuthorizationSystem;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ];
    }
}
