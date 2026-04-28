<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;

class BaseController extends ParentController
{
    protected object $typeOptions;

    protected object $categoryOptions;

    protected array $customMessages;

    protected array $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Acara';
        $this->baseRouteName = 'dashboard.admin.event.stage.';
        $this->baseViewPath = 'dashboard.admin.event.stage.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'number' => 'Nomor Acara',
            'master_match_type_id' => __('Gaya'),
            'master_match_category_id' => __('Kategori'),
        ];
    }

    protected function generateOptions()
    {
        $this->typeOptions = MasterMatchType::pluck('name', 'id');
        $this->categoryOptions = MasterMatchCategory::pluck('name', 'id');

        $this->globalData = [
            'typeOptions' => $this->typeOptions,
            'categoryOptions' => $this->categoryOptions,
        ] + $this->globalData;
    }
}
