<?php

namespace App\Http\Controllers\Dashboard\Admin\Master\School;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;

class BaseController extends ParentController
{
    protected array $customMessages;

    protected array $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = __('Sekolah');
        $this->baseRouteName = 'dashboard.admin.master.school.';
        $this->baseViewPath = 'dashboard.admin.master.school.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => __('Sekolah'),
        ];
    }
}
