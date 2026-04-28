<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Category;

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

        $this->moduleName = 'Kategori Kompetisi';
        $this->baseRouteName = 'dashboard.admin.event.category.';
        $this->baseViewPath = 'dashboard.admin.event.category.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
    }
}
