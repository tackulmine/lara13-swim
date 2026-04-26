<?php

namespace App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event\Participant;

use App\Libraries\FormFields;
use App\Models\ChampionshipEvent;
use App\Models\UserChampionship;
use Illuminate\Http\Request;

class EditController extends BaseController
{
    public function __invoke(
        Request $request,
        ChampionshipEvent $event,
        UserChampionship $userChampionship
    ) {
        $formFields = new FormFields($userChampionship);
        $formFields = $formFields->generateForm();

        $event->load('masterChampionship');
        $userChampionship->load(['user', 'masterChampionshipGaya']);
        $this->generateOptions();

        $breadcrumbs = [
            route('dashboard.admin.kejuaraan.event.index') => 'Kejuaraan',
            route($this->baseRouteName.'index', $event) => $event->masterChampionship->name,
            '' => "Edit {$this->moduleName}",
        ];

        $this->globalData = [
            'pageTitle' => "Edit {$this->moduleName} ".$userChampionship->user->username.'
                ('.(optional($userChampionship->user)->name ?? '-').')',
            'breadcrumbs' => $breadcrumbs,
            'event' => $event,
            'participant' => $formFields,
            'userChampionship' => $userChampionship,
            'id' => $userChampionship->id,
        ] + $this->globalData;

        if (request()->ajax()) {
            return view($this->baseViewPath.'_form', $this->globalData)->render();
        }

        return view($this->baseViewPath.'edit', $this->globalData);
    }
}
