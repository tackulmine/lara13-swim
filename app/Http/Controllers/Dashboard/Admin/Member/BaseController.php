<?php

namespace App\Http\Controllers\Dashboard\Admin\Member;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;
use App\Models\MasterSchool;
use App\Models\MasterUserType;

class BaseController extends ParentController
{
    protected $customMessages;

    protected $customAttributes;

    protected $genderOptions;

    protected $relegionOptions;

    protected $educationOptions;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = __('Atlet');
        $this->baseRouteName = 'dashboard.admin.member.';
        $this->baseViewPath = 'dashboard.admin.member.';
        $this->genderOptions = getGenders();
        $this->relegionOptions = getRelegions();
        $this->educationOptions = getEducations();

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [
            'photo.max' => ':attribute maksimal berukuran 2 MB.',
            'birth_certificate.max' => ':attribute maksimal berukuran 2 MB.',
            'family_card.max' => ':attribute maksimal berukuran 2 MB.',
        ];
        $this->customAttributes = [
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
            'photo' => __('Foto Atlet'),
            'birth_certificate' => __('Akte Kelahiran'),
            'family_card' => __('KK (Kartu Keluarga)'),
            'kta_card' => __('KTA (Kartu Tanda Anggota)'),
        ];
    }

    protected function generateOptions()
    {
        $this->globalData = [
            'masterUserTypeOptions' => MasterUserType::orderBy('name')->pluck('name', 'id'),
            'masterSchoolOptions' => MasterSchool::orderBy('name')->pluck('name', 'name')->prepend('---', ''),
            'relegionOptions' => $this->relegionOptions,
            'genderOptions' => $this->genderOptions,
            'educationOptions' => $this->educationOptions,
        ] + $this->globalData;
    }
}
