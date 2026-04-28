<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Jenssegers\Agent\Facades\Agent;

class BaseController extends Controller
{
    protected array $globalData = [];

    protected string $moduleName = '';

    protected string $baseViewPath = '';

    protected string $baseRouteName = '';

    protected string $parentModuleName = 'Dashboard';

    protected string $parentViewPath = 'dashboard.admin.';

    protected string $parentRouteName = 'dashboard.admin.';

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
