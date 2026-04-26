<?php

namespace App\Http\Controllers\Dashboard\Admin\Master\Participant;

use App\DataTables\Admin\MasterParticipantDataTable;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke(MasterParticipantDataTable $dataTable)
    {
        // return $dataTable->render('users.index');

        $this->globalData = [
            'pageTitle' => "Daftar {$this->moduleName}",
        ] + $this->globalData;

        return $dataTable->render($this->baseViewPath.'datatable', $this->globalData);
    }
}
