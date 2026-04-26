<?php

namespace App\Http\Controllers\Dashboard\Admin\Master\Participant;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;
use App\Models\MasterSchool;

class BaseController extends ParentController
{
    protected $schoolOptions;

    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Peserta';
        $this->baseRouteName = 'dashboard.admin.master.participant.';
        $this->baseViewPath = 'dashboard.admin.master.participant.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'name' => __('Atlet'),
            'gender' => __('Gender'),
            'master_school_id' => __('Sekolah'),
            // 'address' => 'Alamat',
            // 'location' => 'Lokasi',
            // 'birth_date' => 'Tanggal Lahir',
            'birth_year' => __('Tahun Lahir'),
        ];
    }

    protected function generateOptions()
    {
        $this->schoolOptions = MasterSchool::orderBy('name')
            ->pluck('name', 'id')
            ->prepend('-- '.__('Sekolah').' --', '');

        $this->globalData = [
            'schoolOptions' => $this->schoolOptions,
        ] + $this->globalData;
    }
}
