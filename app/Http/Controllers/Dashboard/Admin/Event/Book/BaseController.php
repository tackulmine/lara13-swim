<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Book;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;

class BaseController extends ParentController
{
    public function __construct()
    {
        parent::__construct();

        $this->parentModuleName = __('Event');
        $this->parentRouteName = $this->parentRouteName.'event.';
        $this->parentViewPath = $this->parentViewPath.'event.';

        $this->moduleName = 'Urutan '.__('Event Book');
        $this->baseRouteName = 'dashboard.admin.event.book.';
        $this->baseViewPath = 'dashboard.admin.event.book.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
    }
}
