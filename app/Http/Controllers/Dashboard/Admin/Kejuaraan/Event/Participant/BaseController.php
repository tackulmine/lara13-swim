<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event\Participant;

use App\Http\Controllers\Dashboard\Admin\BaseController as ParentController;
use App\Models\MasterChampionshipGaya;
use App\Models\User;

class BaseController extends ParentController
{
    protected $participantOptions;

    protected $gayaOptions;

    protected $customMessages;

    protected $customAttributes;

    public function __construct()
    {
        parent::__construct();

        $this->moduleName = 'Peserta';
        $this->baseRouteName = 'dashboard.admin.kejuaraan.event.participant.';
        $this->baseViewPath = 'dashboard.admin.kejuaraan.event.participant.';

        $this->globalData = array_merge($this->globalData, [
            'baseRouteName' => $this->baseRouteName,
            'baseViewPath' => $this->baseViewPath,
            'moduleName' => $this->moduleName,
        ]);
        $this->customMessages = [];
        $this->customAttributes = [
            'user_id' => 'Nama',
            'master_championship_gaya_id' => __('Gaya'),
            'point_text' => 'Poin',
            'rank' => 'Ranking',
        ];
    }

    protected function generateOptions()
    {
        $this->participantOptions = User::whereHas('userMember', function ($q) {
            $q->select('id');
        })->orderBy('name')->get(['id', 'username', 'name']);

        $this->participantOptions = $this->participantOptions->mapWithKeys(function ($participant) {
            return [$participant->id => $participant->name." ( {$participant->username} )"];
        });

        $this->gayaOptions = MasterChampionshipGaya::orderByRaw('CAST(`name` AS UNSIGNED) ASC')->pluck('name', 'id');

        $this->globalData = [
            'participantOptions' => $this->participantOptions,
            'gayaOptions' => $this->gayaOptions,
        ] + $this->globalData;
    }
}
