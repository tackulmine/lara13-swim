<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant\Detail;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;

class BaseController extends ParentController
{
    protected $participantOptions;

    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Peserta Estafet';
        $this->baseRouteName = 'dashboard.admin.event.stage.session.participant.detail.';
        $this->baseViewPath = 'dashboard.admin.event.stage.session.participant.detail.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [
            // 'participants.*.master_participant_id.distinct' => ' tidak boleh duplikat.',
        ];
        $this->customAttributes = [
            'participants.*.master_participant_id' => 'Nama '.__('Atlet'),
            'participants.*.ordering' => 'Urutan',
        ];
    }
}
