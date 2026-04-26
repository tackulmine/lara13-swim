<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event\Participant;

use App\Libraries\FormFields;
use App\Models\ChampionshipEvent;
use App\Models\UserChampionship;
use Illuminate\Http\Request;

class CreateBatchController extends BaseController
{
    public function __invoke(
        Request $request,
        ChampionshipEvent $event,
        UserChampionship $userChampionship
    ) {
        $formFields = new FormFields($userChampionship);
        $formFields = $formFields->generateForm();

        $event->load('masterChampionship');
        $this->generateOptions();

        $breadcrumbs = [
            route('dashboard.admin.kejuaraan.event.index') => 'Kejuaraan',
            route($this->baseRouteName.'index', $event) => $event->masterChampionship->name,
            '' => "Buat Batch {$this->moduleName}",
        ];

        $this->globalData = [
            'pageTitle' => "Buat Batch {$this->moduleName}",
            'breadcrumbs' => $breadcrumbs,
            'event' => $event,
            'participant' => $formFields,
            'participantOptions' => $this->participantOptions->prepend('-- select --', ''),
            'gayaOptions' => $this->gayaOptions->prepend('-- select --', ''),
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form-batch', $this->globalData)->render();
        }

        return view($this->baseViewPath.'create-batch', $this->globalData);
    }
}
