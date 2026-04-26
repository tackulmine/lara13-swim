<?php

namespace App\Http\Controllers\Dashboard\Admin\External\Rank;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;

class BaseController extends ParentController
{
    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Ranking';
        $this->baseRouteName = 'dashboard.admin.external.rank.';
        $this->baseViewPath = 'dashboard.admin.external.rank.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => 'Ranking',
        ];
    }
}
