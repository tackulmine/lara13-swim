<?php

namespace App\Http\Controllers\Dashboard\Admin\Master\School;

use App\DataTables\Admin\MasterSchoolDataTable;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke(MasterSchoolDataTable $dataTable)
    {
        // return $dataTable->render('users.index');

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
        ] + $this->globalData;

        return $dataTable->render($this->baseViewPath.'datatable', $this->globalData);
    }
}
