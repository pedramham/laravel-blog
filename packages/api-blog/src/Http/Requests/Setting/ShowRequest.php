<?php

namespace Admin\ApiBolg\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|int',
        ];
    }
}
