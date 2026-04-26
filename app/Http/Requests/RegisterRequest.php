<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users|alpha_dash|min:2|max:50',
            'email' => 'required|string|email|max:255|unique:users|exists:invitations,email',
            'password' => 'required|string|min:8|alpha_num|confirmed',
            'password_confirmation' => 'required|string|min:8|alpha_num',
            'gender' => 'required|in:male,female',
            'relegion' => 'required|in:'.implode(',', array_keys(getRelegions())),
            'last_education' => 'required|in:'.implode(',', array_keys(getEducations())),
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:500',
            'phone_number' => 'required',
            'photo' => 'required|image|max:2048|mimes:jpg,jpeg,png|dimensions:min_width=300,min_height=400',
            'birth_certificate' => 'required|image|max:2048|mimes:jpg,jpeg,png|dimensions:min_width=600,min_height=800',
            'family_card' => 'required|image|max:2048|mimes:jpg,jpeg,png|dimensions:min_width=800,min_height=600',
            'signature_data' => 'required|string',
            'agreement' => 'required|accepted',
        ];

        if ($this->hasFile('photo')) {
            $rules = [
                'photo' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=300,min_height=400',
                ],
            ] + $rules;
        }
        if ($this->hasFile('birth_certificate')) {
            $rules = [
                'birth_certificate' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=600,min_height=800',
                ],
            ] + $rules;
        }
        if ($this->hasFile('family_card')) {
            $rules = [
                'family_card' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=800,min_height=600',
                ],
            ] + $rules;
        }
        if ($this->hasFile('kta_card')) {
            $rules = [
                'kta_card' => [
                    'image',
                    'max:2048', // 2MB
                    'mimes:jpg,jpeg,png',
                    'dimensions:min_width=400,min_height=300',
                ],
            ] + $rules;
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'name' => __('Nama Lengkap Atlet'),
            'username' => __('Username/Panggilan'),
            'email' => __('Email Address'),
            'password' => __('Password'),
            'password_confirmation' => __('Konfirmasi Password'),
            'gender' => __('Gender'),
            'relegion' => __('Agama'),
            'last_education' => __('Pendidikan Terakhir'),
            'address' => __('Alamat'),
            // 'location'              => __('Kec, Kab/Kota'),
            'birth_place' => __('Tempat Lahir'),
            'birth_date' => __('Tanggal Lahir'),
            'phone_number' => __('No. Telp/ WhatsApp (WA)'),
            'height' => __('Tinggi badan'),
            'weight' => __('Berat badan'),
            // 'master_school'         => __('Sekolah'),
            // 'master_member_type_id' => __('Status Atlit'),
            'birth_certificate' => __('Akte Kelahiran'),
            'family_card' => __('KK (Kartu Keluarga)'),
            'kta_card' => __('KTA (Kartu Tanda Anggota)'),
            'photo' => __('Foto Atlet'),
            'birth_certificate' => __('Akte Kelahiran'),
            'family_card' => __('KK (Kartu Keluarga)'),
            'signature_data' => __('Tanda Tangan'),
            'agreement' => __('Persetujuan'),
        ];
    }
}
