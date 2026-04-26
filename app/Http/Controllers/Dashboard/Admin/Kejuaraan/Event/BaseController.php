<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;

class BaseController extends ParentController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Kejuaraan';
        $this->baseRouteName = 'dashboard.admin.kejuaraan.event.';
        $this->baseViewPath = 'dashboard.admin.kejuaraan.event.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => 'Nama Kejuaraan',
            'address' => 'Alamat Kejuaraan',
            'location' => 'Lokasi Kejuaraan',
            'date' => 'Tanggal Kejuaraan',
        ];
    }
}
