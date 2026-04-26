<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Jenssegers\Agent\Facades\Agent;

class BaseController extends Controller
{
    protected $globalData = [];

    protected $moduleName = '';

    protected $baseViewPath = '';

    protected $baseRouteName = '';

    protected $parentModuleName = 'Dashboard';

    protected $parentViewPath = 'dashboard.admin.';

    protected $parentRouteName = 'dashboard.admin.';

    public function __construct()
    {
        $this->middleware('auth');

        $this->globalData = [
            'parentViewPath' => $this->parentViewPath,
            'parentRouteName' => $this->parentRouteName,
            'agent' => ['isMobile' => Agent::isMobile()],
        ];
    }
}
