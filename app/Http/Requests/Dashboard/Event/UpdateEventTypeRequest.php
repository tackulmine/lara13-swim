<?php

namespace App\Http\Requests\Dashboard\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventTypeRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'types' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'types' => __('Gaya').' Kompetisi',
        ];
    }
}
