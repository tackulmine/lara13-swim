<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRegistrationSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'coach_name' => 'required|max:255',
            'coach_phone' => 'required',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'coach_name' => 'Nama Lengkap Pelatih',
            'coach_phone' => 'No HP/WA Pelatih',
        ];
    }

    /*
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    // public function messages()
    // {
    //     return [
    //         'school_certificate.max' => ':attribute maksimal berukuran 2 MB',
    //         'birth_certificate.max' => ':attribute maksimal berukuran 2 MB',
    //         'photo.max' => ':attribute maksimal berukuran 2 MB',
    //     ];
    // }
}
