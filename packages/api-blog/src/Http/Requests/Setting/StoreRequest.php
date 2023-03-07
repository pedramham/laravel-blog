<?php

namespace Admin\ApiBolg\Http\Requests\Setting;

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
            'app_logo' => 'nullable|string',
            'app_favicon' => 'nullable|string',
            'app_name' => 'required|string',
            'app_title' => 'nullable|string',
            'app_description' => 'nullable|string',
            'app_short_description' => 'nullable|string',
            'app_keywords' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|string',
            'mobile' => 'nullable|string',
            'fax' => 'nullable|string',
            'telegram' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'facebook' => 'nullable|string',
            'twitter' => 'nullable|string',
            'instagram' => 'nullable|string',
            'youtube' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'pinterest' => 'nullable|string',
            'github' => 'nullable|string',
        ];
    }
}
