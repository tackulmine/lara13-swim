<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected array $globalData = [];

    protected string $moduleName = '';

    protected string $baseViewPath = '';

    protected string $baseRouteName = '';

    protected string $parentModuleName = 'Dashboard';

    protected string $parentViewPath = 'dashboard.';

    protected string $parentRouteName = 'dashboard.';

    public function __construct()
    {
        $this->middleware('auth');

        $this->globalData = [
            'parentViewPath' => $this->parentViewPath,
            'parentRouteName' => $this->parentRouteName,
        ];
    }
}
