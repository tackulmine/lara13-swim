<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;
use App\Models\EventStage;

class BaseController extends ParentController
{
    protected $stageOptions;

    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Seri';
        $this->baseRouteName = 'dashboard.admin.event.stage.session.';
        $this->baseViewPath = 'dashboard.admin.event.stage.session.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'session' => 'Nomor Seri',
            'event_stage_id' => 'Nomor Acara',
        ];
    }

    protected function generateOptions($eventId)
    {
        $this->stageOptions = EventStage::whereEventId($eventId)->get(['id', 'number']);

        $this->stageOptions = $this->stageOptions->mapWithKeys(function ($eventStage) {
            return [$eventStage->id => str_pad($eventStage->number, 3, 0, STR_PAD_LEFT)];
        });

        $this->globalData = [
            'stageOptions' => $this->stageOptions,
        ] + $this->globalData;
    }
}
