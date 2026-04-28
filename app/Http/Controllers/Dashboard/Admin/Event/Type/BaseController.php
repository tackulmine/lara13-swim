<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Type;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;

class BaseController extends ParentController
{
    // protected $parentViewPath = 'dashboard.admin.';

    // protected $parentRouteName = 'dashboard.admin.';

    public function __construct()
    {
        parent::__construct();

        $this->parentModuleName = 'Kompetisi';
        $this->parentRouteName = $this->parentRouteName.'event.';
        $this->parentViewPath = $this->parentViewPath.'event.';

        $this->moduleName = __('Gaya').' Kompetisi';
        $this->baseRouteName = 'dashboard.admin.event.type.';
        $this->baseViewPath = 'dashboard.admin.event.type.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
    }
}
