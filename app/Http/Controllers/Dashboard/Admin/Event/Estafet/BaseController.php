<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Estafet;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;

class BaseController extends ParentController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Peserta Estafet';
        $this->parentRouteName = 'dashboard.admin.event.';
        $this->baseRouteName = 'dashboard.admin.event.estafet.';
        $this->baseViewPath = 'dashboard.admin.event.estafet.';

        $this->globalData = array_merge($this->globalData, [
            'parentRouteName' => $this->parentRouteName,
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
    }

    protected function generateFormAttributes()
    {
        $this->customMessages = [];
        $this->customAttributes = [
            // 'name'         => 'Nama Acara',
        ];
    }
}
