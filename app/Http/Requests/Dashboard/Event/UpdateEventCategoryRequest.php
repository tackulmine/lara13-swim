<?php

namespace App\Http\Requests\Dashboard\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'categories' => 'sometimes|nullable|array',
        ];
    }

    public function attributes()
    {
        return [
            'categories' => 'Kategori Kompetisi',
        ];
    }
}
