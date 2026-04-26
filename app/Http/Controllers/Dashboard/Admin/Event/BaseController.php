<?php

namespace App\Http\Controllers\Dashboard\Admin\Event;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;

class BaseController extends ParentController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Kompetisi';
        $this->baseRouteName = 'dashboard.admin.event.';
        $this->baseViewPath = 'dashboard.admin.event.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
    }

    protected function generateFormAttributes()
    {
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => 'Nama Kompetisi',
            'slug' => 'Slug Kompetisi',
            'address' => 'Alamat Kompetisi',
            'location' => 'Lokasi Kompetisi',
            'date' => 'Tanggal Kompetisi',
            'is_reg' => 'Aktifkan Pendaftaran',
            'reg_end_date' => 'Tanggal Akhir Pendaftaran',
            'reg_quota' => 'Kuota Pendaftaran',
            'photo' => 'Logo Kompetisi',
            'photo_right' => 'Logo Kanan Kompetisi',
        ];
    }
}
