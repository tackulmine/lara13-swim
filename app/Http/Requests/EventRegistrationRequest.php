<?php

namespace App\Http\Requests;

use App\Models\Event;
use App\Models\MasterMatchCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventRegistrationRequest extends FormRequest
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
        // logger()->info('request: ');
        // logger()->info(json_encode($this->toArray()));
        // logger()->info('route: ');
        // logger()->info($this->route('eventSlug'));
        $event = Event::whereSlug($this->route('eventSlug'))->firstOrFail();
        $category = MasterMatchCategory::where('id', $this->category)->first();
        // logger()->info('event: ');
        // logger()->info($event->name);

        $rules = [
            'name' => 'required|max:255',
            'birth_year' => 'required|integer|digits:4|min:'.(now()->subYear(50)->year).'|max:'.(now()->subYear()->year),
            'gender' => [
                'required',
                Rule::in(['male', 'female', 'mix']),
            ],
            'category' => 'required|max:255',
            'school' => 'required|max:255',
            'style' => [
                'required',
                'array',
                // 'min:4',
                function ($attribute, $values, $fail) use ($event, $category) {
                    $min = $event->reg_style_min ?? 1;

                    $regStyleMinCats = $event->reg_cat_style_min;
                    if (! empty($regStyleMinCats)) {
                        $regStyleMinCatKeys = array_keys($regStyleMinCats);
                        if (in_array($category->id, $regStyleMinCatKeys)) {
                            $min = $regStyleMinCats[$category->id] ?? $min;
                        }
                    }

                    // bypass logged in user
                    if (auth()->check()) {
                        $min = 1;
                    }

                    if ($category && Str::contains(strtolower($category->name), 'relay')) {
                        $min = 1;
                    }

                    if (! empty($min) && count($values) < $min) {
                        // $fail('Pilih minimal '.$min.' '.$attribute.'.');
                        $fail('Pilih minimal '.$min.' '.__('Gaya').'.');
                    }
                },
            ],
            // 'school_certificate' => 'required|file|max:2048|mimes:jpg,jpeg,png,pdf',
            'birth_certificate' => [
                // 'required',
                // Rule::requiredIf(function() use ($category) {
                //     return ($category && ! Str::contains(strtolower($category->name), 'relay'));
                // }),
                Rule::requiredIf(fn () => ($category && ! Str::contains(strtolower($category->name), 'relay'))),
                'file',
                'max:2048',
                'mimes:jpg,jpeg,png',
                'dimensions:min_width=600,min_height=800',
            ],
            'photo' => [
                // 'required',
                Rule::requiredIf(fn () => ($category && ! Str::contains(strtolower($category->name), 'relay'))),
                'file',
                'max:2048',
                'mimes:jpg,jpeg,png',
                'dimensions:min_width=300,min_height=400',
            ],
            'coach_name' => 'required|max:255',
            'coach_phone' => 'required',
        ];

        // if (
        //     $event->eventSpecialTypes->isNotEmpty()
        //     && $this->input('register_as')
        //     && $event->eventSpecialTypes->pluck('name')->contains($this->input('register_as'))
        // ) {
        //     $rules['school'] = [
        //         'required',
        //         'max:255',
        //         Rule::in($event->eventSpecialTypes->pluck('name')->toArray()),
        //     ];
        // }

        if (auth()->check()) {
            if (! empty($rules['school_certificate'])) {
                unset($rules['school_certificate']);
            }
            if (! empty($rules['birth_certificate'])) {
                unset($rules['birth_certificate']);
            }
            if (! empty($rules['photo'])) {
                unset($rules['photo']);
            }
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $event = Event::whereSlug($this->route('eventSlug'))->firstOrFail();

        $attributes = [
            'name' => 'Nama Lengkap',
            'birth_year' => 'Tahun Lahir',
            'gender' => __('Gender'),
            'category' => __('Kategori'),
            'school' => __('Nama Sekolah'),
            'style' => __('Gaya'),
            'school_certificate' => 'Surat Keterangan Sekolah',
            'birth_certificate' => 'Akta Kelahiran',
            'photo' => 'Foto Atlet',
            'coach_name' => 'Nama Pelatih',
            'coach_phone' => 'No. HP/WA Pelatih',
        ];
        if (
            $event->eventSpecialTypes->isNotEmpty()
            && $this->input('register_as')
            && $event->eventSpecialTypes->pluck('name')->contains($this->input('register_as'))
        ) {
            $attributes['birth_certificate'] = 'SK / KTA';
        }

        return $attributes;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'school_certificate.max' => ':attribute maksimal berukuran 2 MB.',
            'birth_certificate.max' => ':attribute maksimal berukuran 2 MB.',
            'photo.max' => ':attribute maksimal berukuran 2 MB.',
            'style.min' => 'Pilih minimal :min :attribute.',
        ];
    }
}
