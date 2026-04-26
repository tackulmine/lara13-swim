<?php

namespace App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;
use App\Models\MasterParticipant;

class BaseController extends ParentController
{
    protected $participantOptions;

    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Peserta';
        $this->baseRouteName = 'dashboard.admin.event.stage.session.participant.';
        $this->baseViewPath = 'dashboard.admin.event.stage.session.participant.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'master_participant_id' => 'Peserta',
            'track' => 'Lintasan',
            'point' => 'Poin Waktu',
            'point_text' => 'Poin Waktu',
            'disqualification' => 'Dis',
            'notes' => 'Keterangan',
        ];
    }

    protected function generateOptions()
    {
        $masterParticipants = MasterParticipant::has('masterSchool')
            ->with('masterSchool')
            ->orderBy('name')
            ->get();

        $this->participantOptions = $masterParticipants
            ->pluck('name_detail_with_school', 'id');
        // ->prepend('-- pilih --', '');

        $this->globalData = [
            'participantOptions' => $this->participantOptions,
        ] + $this->globalData;
    }
}
