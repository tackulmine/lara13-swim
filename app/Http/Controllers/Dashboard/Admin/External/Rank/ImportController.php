<?php

namespace App\Http\Controllers\Dashboard\Admin\External\Rank;

use Illuminate\Http\Request;

class ImportController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke(Request $request)
    {
        $this->globalData = [
            'pageTitle' => 'Import Atlet',
            'moduleName' => 'Atlet',
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_import_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'import', $this->globalData);
    }
}
