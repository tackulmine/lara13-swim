<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $globalData = [];

    protected $moduleName = '';

    protected $baseViewPath = '';

    protected $baseRouteName = '';

    protected $parentModuleName = 'Dashboard';

    protected $parentViewPath = 'dashboard.';

    protected $parentRouteName = 'dashboard.';

    public function __construct()
    {
        $this->middleware('auth');

        $this->globalData = [
            'parentViewPath' => $this->parentViewPath,
            'parentRouteName' => $this->parentRouteName,
        ];
    }
}
